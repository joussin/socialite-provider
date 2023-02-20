<?php

namespace ApiOAuthSdk\Services;

use GuzzleHttp\RequestOptions;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\ProviderInterface;
use Laravel\Socialite\Two\User;

class MbcUserProvider extends AbstractProvider implements ProviderInterface
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


    public const API_URL = 'http://127.0.0.1:9999';

    public const CODE_VERIFIER = 'P6oelECwFb5dACCIeafOu6DO2gfBMsOupeap1CiWRg3U3n9PE2tzrsY93xsXonyGkKFYqLexiKVdQ8wPsaRsdrKloR7VvxJ9sIKDEsKJWioeex7kB8NQjucObr2mPjs2';
//   request()->session()->put('code_verifier', $this->code_verifier);

    /**
     * {@inheritdoc}
     */
    protected function getAuthUrl($state)
    {
        return $this->getLoginUrl();
    }


    public function getLoginUrl(): string
    {
        $scopes = 'route:read route:create';

        $state = \Illuminate\Support\Str::random(40);

        $codeChallenge = strtr(rtrim(
            base64_encode(hash('sha256', self::CODE_VERIFIER, true))
            , '='), '+/', '-_');

        $data = [
            'client_id'             => 5,
            'redirect_uri'          => env('MBC_OAUTH_URL_CALLBACK'),
            'response_type'         => 'code',
            'scope'                 => $scopes,
            'state'                 => $state,
            'code_challenge'        => $codeChallenge,
            'code_challenge_method' => 'S256',
//             'prompt' => 'consent', // "none", "consent", or "login"
        ];

        $query = http_build_query($data);

        return self::API_URL . '/oauth/authorize?' . $query;
    }


    /**
     * {@inheritdoc}
     */
    protected function getTokenUrl()
    {
        return self::API_URL . '/oauth/token';
    }

    protected function getTokenFields($code)
    {
        $fields = [
            'grant_type'    => 'authorization_code',
            'client_id'     => $this->clientId,
            'client_secret' => $this->clientSecret,
            'code'          => $code,
            'redirect_uri'  => $this->redirectUrl,
        ];

        if ($this->usesPKCE()) {
//            $fields['code_verifier'] = $this->request->session()->pull('code_verifier');

            $fields['code_verifier'] = self::CODE_VERIFIER;
        }

        return $fields;
    }

    /**
     * {@inheritdoc}
     */
    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->get(self::API_URL . '/oauth/userinfo', [
            RequestOptions::QUERY   => [
                'prettyPrint' => 'false',
            ],
            RequestOptions::HEADERS => [
                'Accept'        => 'application/json',
                'Authorization' => 'Bearer ' . $token,
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
        $user['name'] = Arr::get($user, 'name');
        $user['email'] = Arr::get($user, 'email');
//        $user['verified_email'] = Arr::get($user, 'email_verified');

        return (new User())->setRaw($user)->map([
            'id'    => Arr::get($user, 'id'),
            'name'  => Arr::get($user, 'name'),
            'email' => Arr::get($user, 'email'),
        ]);
    }
}
