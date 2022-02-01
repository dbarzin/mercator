<?php

namespace App\Providers;

use App\Ldap\Scopes\OnlyOrgUnitUser;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;
use LdapRecord\Models\OpenLDAP\User;

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

        User::addGlobalScope(
            new OnlyOrgUnitUser
        );
    }
}
