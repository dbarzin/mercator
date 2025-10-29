<?php


namespace App\Services;

use Illuminate\Support\Arr;
use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\InvalidStateException;
use Laravel\Socialite\Two\ProviderInterface;

class KeycloakProviderService extends AbstractProvider implements ProviderInterface
{
    public function user(): array
    {
        if ($this->hasInvalidState()) {
            throw new InvalidStateException();
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

    protected function getAuthUrl($state): string
    {
        return $this->buildAuthUrlFromBase(
            config('services.keycloak.base_url').'/realms/daara/protocol/openid-connect/auth',
            $state
        );
    }

    protected function getTokenUrl(): string
    {
        return config('services.keycloak.base_url').'/realms/daara/protocol/openid-connect/token';
    }

    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->get(
            config('services.keycloak.base_url').'/realms/daara/protocol/openid-connect/userinfo',
            [
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer '.$token,
                ],
            ]
        );

        return json_decode($response->getBody(), true);
    }

    protected function mapUserToObject(array $user): array
    {
        return [
            'id' => $user['sub'],
            'name' => $user['name'],
            'email' => $user['email'],
        ];
    }

    protected function getTokenFields($code): array
    {
        return [
            'grant_type' => 'authorization_code',
            'client_id' => config('services.keycloak.client_id'),
            'client_secret' => config('services.keycloak.client_secret'),
            'redirect_uri' => config('services.keycloak.redirect'),
            'code' => $code,
        ];
    }

    protected function getCodeFields($state = null): array
    {
        return array_merge(
            parent::getCodeFields($state),
            ['response_mode' => 'query']
        );
    }
}
