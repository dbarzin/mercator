<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Keycloak SSO Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for Keycloak Single Sign-On integration.
    |
    */

    'keycloak' => [
        'enabled' => env('KEYCLOAK_ENABLED', false),

        'client_id' => env('KEYCLOAK_CLIENT_ID'),
        'client_secret' => env('KEYCLOAK_CLIENT_SECRET'),
        'redirect' => env('KEYCLOAK_REDIRECT_URI'),

        // Base URL du serveur Keycloak
        'base_url' => env('KEYCLOAK_BASE_URL'),

        // Nom du realm Keycloak
        'realm' => env('KEYCLOAK_REALM'),

        /*
        |--------------------------------------------------------------------------
        | Auto-Provisioning
        |--------------------------------------------------------------------------
        |
        | Si activé, crée automatiquement un utilisateur local lors de la première
        | connexion SSO réussie. Si désactivé, seuls les utilisateurs existants
        | peuvent se connecter via SSO.
        |
        */
        'auto_provision' => env('KEYCLOAK_AUTO_PROVISION', true),

        /*
        |--------------------------------------------------------------------------
        | Auto-Provision Default Role
        |--------------------------------------------------------------------------
        |
        | Rôle attribué par défaut lors de l'auto-provisioning.
        | Doit correspondre au 'title' d'un Role existant.
        |
        */
        'auto_provision_role' => env('KEYCLOAK_AUTO_PROVISION_ROLE', 'User'),

        /*
        |--------------------------------------------------------------------------
        | Fallback to Local Authentication
        |--------------------------------------------------------------------------
        |
        | Si activé, permet de basculer vers l'authentification locale (login/password)
        | en cas d'échec de l'authentification Keycloak ou si l'utilisateur n'existe
        | pas localement.
        |
        | Comportement :
        | - true  : Redirige vers le formulaire de login local avec message informatif
        | - false : Affiche une erreur et bloque l'accès
        |
        */
        'fallback_local' => env('KEYCLOAK_FALLBACK_LOCAL', false),

        /*
        |--------------------------------------------------------------------------
        | Sync Roles from Keycloak
        |--------------------------------------------------------------------------
        |
        | Si activé, synchronise les rôles Keycloak avec les rôles locaux
        | correspondants (basé sur le 'title' du rôle).
        |
        */
        'sync_roles' => env('KEYCLOAK_SYNC_ROLES', true),
    ],

];