<?php

namespace App\Providers;

use App\Ldap\Scopes\OnlyOrgUnitUser;
use App\Ldap\LdapUser;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;
use App\MApplication;
use App\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        if (! app()->runningInConsole()) {
            Passport::routes();
        }

        // LDAP Restrictions on connection
        LDAPuser::addGlobalScope(new OnlyOrgUnitUser);

        /********************************************************
         *  Register one Gate per model for cartographers.
         * ******************************************************/
        /**
         * Before check
         */
        Gate::before(function (User $user, $ability) {
            // Si c'est un admin, on lui autorise toutes les applications
            if ($user->isAdmin() || !config('app.cartographers', false)) {
                return true;
            }
        });

        /**
         * MApplication
         */
        Gate::define('is-cartographer-m-application', function (User $user, MApplication $application) {
            return $application->hasCartographer($user);
        });
    }
}
