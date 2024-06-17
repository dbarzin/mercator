<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\User;
use App\Role; // Importez le modèle Role

class SsoController extends Controller
{
    public function redirectToKeycloak()
    {
        return Socialite::driver('keycloak')->redirect();
    }

    public function handleKeycloakCallback()
    {
        try {
            $keycloakUser = Socialite::driver('keycloak')->user();
        } catch (\Exception $e) {
            // Gérer l'erreur d'authentification
            return redirect()->route('login')->with('error', 'Authentication failed.');
        }

        // Récupérer les rôles de Keycloak
        $roles = $keycloakUser->user['realm_access']['roles'];

        // Trouver ou créer l'utilisateur dans la base de données locale
        $existingUser = User::where('email', $keycloakUser->email)->first();

        if (!$existingUser) {
            $existingUser = new User();
            $existingUser->name = $keycloakUser->name; // Supposons que Keycloak fournit le nom de l'utilisateur
            $existingUser->email = $keycloakUser->email;
            $existingUser->save();
        }

        // Associer les rôles à l'utilisateur dans la base de données locale
        foreach ($roles as $role) {
            $roleModel = Role::where('title', $role)->first();
            if ($roleModel) {
                $existingUser->roles()->syncWithoutDetaching($roleModel->id);
            }
        }

        // Connecter l'utilisateur
        Auth::login($existingUser);

        // Rediriger vers la page souhaitée après la connexion
        return redirect()->intended('/');
    }
}