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

    public function handleKeycloakCallback()
    {
        try {
            $keycloakUser = Socialite::driver('keycloak')->user();
        } catch (\Exception $e) {
            // Gérer l'erreur d'authentification
            return redirect()->route('login')->with('error', 'Authentication failed.');
        }

        // Récupérer les rôles de Keycloak
        try {
            $roles = $keycloakUser->user['realm_access']['roles'];
        } catch (\Exception $e) {
            $roles = [];
        }

        // Trouver ou créer l'utilisateur dans la base de données locale
        $existingUser = User::where('email', $keycloakUser->email)->first();

        if (! $existingUser) {
            if (! config('services.keycloak.auto_provisioning', true)) {
                return redirect()
                    ->route('login')
                    ->with('message', 'User "' . $keycloakUser->email. '" is not a valid Mercator user.');
            }
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
