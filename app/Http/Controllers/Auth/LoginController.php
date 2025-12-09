<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use LdapRecord\Auth\BindException;
use LdapRecord\Container;
use LdapRecord\Models\Entry as LdapEntry;
use Mercator\Core\Models\User;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected string $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    // ✅ Le framework utilisera toujours ce champ
    public function username(): string
    {
        return 'login';
    }

    /**
     * Hook appelé APRES un login réussi (LDAP ou local).
     *
     * Ici on :
     *  - eager-load les rôles / permissions pour éviter le N+1 dans la requête
     *  - stocke l'utilisateur enrichi en session pour un éventuel middleware
     */
    protected function authenticated(Request $request, User $user): void
    {
        $user->loadMissing('roles.permissions');

        session([
            'auth_role_ids'    => $user->roles->pluck('id')->all(),
            'auth_permissions' => $user->roles
                ->flatMap->permissions
                ->pluck('title')
                ->unique()
                ->values()
                ->all(),
        ]);
    }

    /**
     * LDAP bind (LDAPRecord v2)
     */
    protected function ldapBindAndGetUser(string $appUsername, string $password): ?LdapEntry
    {
        if ($appUsername === '' || $password === '') {
            Log::debug('LDAP skipped: empty identifier or password.');

            return null;
        }

        try {
            $query = LdapEntry::query();

            // Optionnel : restreindre à une OU si configuré
            $base = trim((string) config('app.ldap_users_base_dn'));
            if ($base !== '') {
                $query->in($base);
            }

            // Group
            $group = trim((string) config('app.ldap_group'));
            $useNested = (bool) config('app.ldap_nested_groups');

            if ($group !== '') {
                if ($useNested) {
                    // Nested groups for Active Directory using LDAP_MATCHING_RULE_IN_CHAIN
                    // Generates filter: (memberOf:1.2.840.113556.1.4.1941:=<GROUP_DN>)
                    $query->whereRaw(
                        'memberOf',
                        ':1.2.840.113556.1.4.1941:=',
                        $group
                    );
                } else {
                    // Standard non-recursive group membership
                    $query->whereEquals('memberOf', $group);
                }
            }
            // Attributs de login à tester côté LDAP (uid, sAMAccountName, etc.)
            $attrs = array_values(array_filter(array_map('trim', explode(',', (string) config('app.ldap_login_attributes')))));
            if (empty($attrs)) {
                Log::warning('LDAP login aborted: app.ldap_login_attributes is empty.');

                return null;
            }

            // Filtre OR sur les attributs configurés
            $first = true;
            foreach ($attrs as $attr) {
                if ($first) {
                    $query->whereEquals($attr, $appUsername);
                    $first = false;
                } else {
                    $query->orWhereEquals($attr, $appUsername);
                }
            }

            // Collision guard
            $results = $query->limit(2)->get();
            if ($results->count() === 0) {
                Log::debug('LDAP user not found for identifier.', ['identifier' => $appUsername]);

                return null;
            }
            if ($results->count() > 1) {
                Log::warning('LDAP identifier collision: multiple entries match.', [
                    'identifier' => $appUsername,
                    'attributes' => $attrs,
                ]);

                return null;
            }

            /** @var LdapEntry $ldapUser */
            $ldapUser = $results->first();

            $connection = Container::getConnection();
            $dn = $ldapUser->getDn();

            if ($dn && $connection->auth()->attempt($dn, $password, true)) {
                return $ldapUser;
            }

            return null;
        } catch (BindException $e) {
            Log::warning('LDAP bind failed', [
                'error' => $e->getMessage(),
                'diagnostic' => $e->getDetailedError()?->getDiagnosticMessage(),
            ]);

            return null;
        } catch (\Throwable $e) {
            Log::error('LDAP error: '.$e->getMessage());

            return null;
        }
    }

    /**
     * Login avec LDAP optionnel + fallback local (UNIQUEMENT via 'login').
     */
    protected function attemptLogin(Request $request): bool
    {
        $useLdap = (bool) config('app.ldap_enabled');
        $fallbackLocal = (bool) config('app.ldap_fallback_local');
        $autoProvision = (bool) config('app.ldap_auto_provision');

        $credentials = $request->only($this->username(), 'password'); // ['login' => ..., 'password' => ...]
        $identifier = (string) ($credentials[$this->username()] ?? '');
        $password = (string) ($credentials['password'] ?? '');
        $remember = $request->boolean('remember');

        if ($useLdap) {
            $ldapUser = $this->ldapBindAndGetUser($identifier, $password);

            if ($ldapUser) {
                // Mapping local UNIQUEMENT par 'login'
                $local = User::where('login', $identifier)->first();

                if (! $local && $autoProvision) {
                    $local = User::create([
                        'name' => $ldapUser->getFirstAttribute('cn') ?: $identifier,
                        'email' => $ldapUser->getFirstAttribute('mail') ?: 'user@localhost.local',
                        'login' => $identifier,
                        'password' => Hash::make(Str::random(32)), // inutilisable en local par défaut
                    ]);
                }

                if ($local) {
                    // 🔐 Login manuel → AuthenticatesUsers appellera ensuite sendLoginResponse()
                    // qui déclenchera le hook authenticated()
                    $this->guard()->login($local, $remember);

                    return true;
                }

                // LDAP OK mais pas d'utilisateur local et pas d’auto-provision
                return false;
            }

            // LDAP KO → éventuel fallback local (toujours via 'login')
            if (! $fallbackLocal) {
                return false;
            }
        }

        // Auth locale (Laravel) — utilisera ['login' => ..., 'password' => ...]
        return $this->guard()->attempt(
            $this->credentials($request), // credentials() retournera login + password car username() = 'login'
            $remember
        );
    }
}
