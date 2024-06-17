<?php

namespace App\Services\V3;

use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\ProviderInterface;
use Illuminate\Support\Facades\Log;
use App\Modules\Api\Modules\V3\Models\User;
use Illuminate\Support\Arr;

class KeycloakService extends AbstractProvider implements ProviderInterface
{
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase(
            config('services.keycloak.base_url') . '/realms/daara/protocol/openid-connect/auth',
            $state
        );
    }

    protected function getTokenUrl()
    {
        return config('services.keycloak.base_url') . '/realms/daara/protocol/openid-connect/token';
    }

    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->get(
            config('services.keycloak.base_url') . '/realms/daara/protocol/openid-connect/userinfo',
            [
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . $token,
                ],
            ]
        );

        return json_decode($response->getBody(), true);
    }

    protected function mapUserToObject(array $user)
    {
        return [
            'id' => $user['sub'],
            'name' => $user['name'],
            'email' => $user['email'],
        ];
    }

    protected function getTokenFields($code)
    {
        return [
            'grant_type' => 'authorization_code',
            'client_id' => config('services.keycloak.client_id'),
            'client_secret' => config('services.keycloak.client_secret'),
            'redirect_uri' => config('services.keycloak.redirect'),
            'code' => $code,
        ];
    }

    protected function getCodeFields($state = null)
    {
        return array_merge(
            parent::getCodeFields($state),
            ['response_mode' => 'query']
        );
    }

    public function user()
    {
        if ($this->hasInvalidState()) {
            throw new InvalidStateException;
        }

        $response = $this->getAccessTokenResponse($this->getCode());

        $user = $this->mapUserToObject($this->getUserByToken(
            $token = Arr::get($response, 'access_token')
        ));

        return array_merge($user, [
            'access_token' => $token,
            'refresh_token' => Arr::get($response, 'refresh_token'),
            'expires_in' => Arr::get($response, 'expires_in'),
        ]);

        
    }
}