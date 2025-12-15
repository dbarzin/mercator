<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // 🧠 Hook global appelé AVANT toutes les autres règles Gate
        Gate::before(function ($user, string $ability) {
            // Si pas d'utilisateur (guest), on ne fait rien
            if (!$user) {
                return null;
            }

            // Récupère la liste des permissions mise en session au login
            $permissions = session('auth_permissions', []);

            // Si la permission demandée est dans la liste → autorisé direct
            if (in_array($ability, $permissions, true)) {
                return true;
            }

            // Sinon, on laisse les autres Gate::define() (ou policies) faire leur travail
            return null;
        });
    }
}
