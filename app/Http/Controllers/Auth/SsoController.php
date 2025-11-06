<?php


namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

// Importez le modèle Role

class SsoController extends Controller
{
    public function redirectToKeycloak()
    {
        return Socialite::driver('keycloak')->redirect();
    }

    / **
     * Handle the Keycloak OAuth callback and authenticate or provision a local user.
     *
     * Retrieves the Keycloak user from Socialite, finds or (optionally) creates a corresponding local User,
     * synchronizes any matching local Role models from the Keycloak roles without detaching existing roles,
     * logs the local user in, and redirects to the intended page. If authentication with Keycloak fails
     * the request is redirected back to the login route with an error; if the local user does not exist and
     * auto-provisioning is disabled, the request is redirected back to the login route with a message.
     *
     * @return \Illuminate\Http\RedirectResponse A redirect response to the intended URL on success, or to the login route with an error/message on failure.
     */
    public function handleKeycloakCallback()
    {
        try {
            $keycloakUser = Socialite::driver('keycloak')->user();
        } catch (\Exception $e) {
            // Gérer l'erreur d'authentification
            return redirect()->route('login')->with('error', 'Authentication failed.');
        }

        // Récupérer les rôles de Keycloak
        $roles = $keycloakUser['user']['realm_access']['roles'] ?? [];

        // Trouver ou créer l'utilisateur dans la base de données locale
        $existingUser = User::where('login', $keycloakUser['nickname'])->first();

        if (! $existingUser) {
            if (! config('services.keycloak.auto_provision', true)) {
                return redirect()
                    ->route('login')
                    ->with('message', 'User "' . $keycloakUser['nickname']. '" is not a valid Mercator user.');
            }
            $existingUser = new User();
            $existingUser->name = $keycloakUser['nickname']; // Supposons que Keycloak fournit le nom de l'utilisateur
            $existingUser->email = $keycloakUser['email'];
            $existingUser->save();
        }

        // Associer les rôles à l'utilisateur dans la base de données locale
        foreach ($roles as $role) {
            $roleModel = Role::where('title', $role)->first();
            if ($roleModel) {
                $existingUser->roles()->syncWithoutDetaching($roleModel);
            }
        }

        // Connecter l'utilisateur
        Auth::login($existingUser);

        // Rediriger vers la page souhaitée après la connexion
        return redirect()->intended('/');
    }
}