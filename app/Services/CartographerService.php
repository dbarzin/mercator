<?php

namespace App\Services;

use App\Http\Middleware\Authenticate;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class CartographerService {

    /**
     * Permet de savoir si l'option 'Cartographes' est active.
     * On laisse l'accès aux admins.
     *
     * @return bool
     */
    public function needProceedForCartographers() : bool
    {
        return !(Auth::user()->getIsAdminAttribute() || !config('app.cartographers', false));
    }

    /**
     * Permet de retirer de la collection les applications où l'utilisateur n'est pas cartographe, si souhaité
     *
     * @param Collection $applications
     * @return Collection
     */
    public function filterOnCartographers(Collection $applications) : Collection
    {
        // Maybe there's a way to TypeHint the authenticated user ? (\Illuminate\Contracts\Auth\Authenticatable user)
        $currentUser = Auth::user();

        if($this->needProceedForCartographers()) {
            foreach ($applications as $key => $application) {
                if(!$application->hasCartographer($currentUser)) {
                    $applications->forget($key);
                }
            }
        }
        return $applications->sortBy('name')->pluck('name', 'id');;
    }
}
