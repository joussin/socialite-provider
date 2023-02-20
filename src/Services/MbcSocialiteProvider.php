<?php

namespace ApiOAuthSdk\Services;

use GuzzleHttp\RequestOptions;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\ProviderInterface;
use Laravel\Socialite\Two\User;

class MbcSocialiteProvider extends AbstractProvider implements ProviderInterface
{
    /**
     * The separating character for the requested scopes.
     *
     * @var string
     */
    protected $scopeSeparator = ' ';

    /**
     * The scopes being requested.
     *
     * @var array
     */
    protected $scopes = [
        'route:create'
    ];


    protected $stateless = true;

    protected $usesPKCE = true;


    /**
     * {@inheritdoc}
     */
    protected function getAuthUrl($state)
    {
        $auth2ApiClient = app()->make(OAuth2ApiClientInterface::class);

        return $auth2ApiClient->getLoginUrl();
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenUrl()
    {
        return 'http://127.0.0.1:9999/oauth/token';
    }

    protected function getTokenFields($code)
    {
        $fields = [
            'grant_type' => 'authorization_code',
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'code' => $code,
            'redirect_uri' => $this->redirectUrl,
        ];

        if ($this->usesPKCE()) {
//            $fields['code_verifier'] = $this->request->session()->pull('code_verifier');

            $fields['code_verifier'] = OAuth2ApiClient::CV;
        }

        return $fields;
    }

    /**
     * {@inheritdoc}
     */
    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->get('http://127.0.0.1:9999/oauth/userinfo', [
            RequestOptions::QUERY => [
                'prettyPrint' => 'false',
            ],
            RequestOptions::HEADERS => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '.$token,
            ],
        ]);

        return json_decode($response->getBody(), true);
    }

    /**
     * {@inheritdoc}
     */
    protected function mapUserToObject(array $user)
    {
        // Deprecated: Fields added to keep backwards compatibility in 4.0. These will be removed in 5.0
        $user['id'] = Arr::get($user, 'id');
//        $user['verified_email'] = Arr::get($user, 'email_verified');
//        $user['link'] = Arr::get($user, 'profile');

        return (new User())->setRaw($user)->map([
            'id' => Arr::get($user, 'id'),
//            'nickname' => Arr::get($user, 'nickname'),
//            'name' => Arr::get($user, 'name'),
//            'email' => Arr::get($user, 'email'),
//            'avatar' => $avatarUrl = Arr::get($user, 'picture'),
//            'avatar_original' => $avatarUrl,
        ]);
    }
}
