<?php

namespace App\Services;

use App\Models\MApplication;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

/**
 * Wrapper pour la logique des cartographes au sein de l'appli.
 */
class CartographerService
{
    protected bool $active;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->active = (bool) Config::get('app.cartographers');
    }

    /**
     * Permet de retirer de la collection les applications où l'utilisateur connecté n'est pas cartographe, si souhaité
     */
    public function filterOnCartographers(Collection $applications): Collection
    {
        // Maybe there's a way to TypeHint the authenticated user ? (\Illuminate\Contracts\Auth\Authenticatable user)
        $currentUser = Auth::user();
        // On ne filtre pas pour les admins
        if (! $this->active || $currentUser->isAdmin()) {
            return $applications->sortBy('name')->pluck('name', 'id');
        }

        foreach ($applications as $key => $application) {
            if (! $application->hasCartographer($currentUser)) {
                $applications->forget($key);
            }
        }

        return $applications->sortBy('name')->pluck('name', 'id');
    }

    /**
     * Permet d'attribuer le rôle de cartographe à ceux de l'application passée en paramètre,
     * si ce rôle existe au sein de l'application
     */
    public function attributeCartographerRole(MApplication $application): void
    {
        if (! $this->active) {
            return;
        }

        // On ne veut pas le créer s'il existe pas
        if (! ($role = Role::getRoleByTitle('Cartographer')) && ! ($role = Role::getRoleByTitle('Cartographe'))) {
            return;
        }

        /** @var User $cartographer */
        foreach ($application->cartographers as $cartographer) {
            // Pas besoin de le mettre sur les utilisateurs étant administrateurs
            if ($cartographer->isAdmin()) {
                continue;
            }

            $cartographer->addRole($role);
        }
    }
}
