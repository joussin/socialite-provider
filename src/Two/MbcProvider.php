<?php

namespace MbcUserProvider\Two;

use GuzzleHttp\RequestOptions;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\ProviderInterface;
use Laravel\Socialite\Two\User;
use MbcUserProvider\Contracts\Server as MbcServerInterface;
use MbcUserProvider\Contracts\User as MbcUserInterface;
use MbcUserProvider\Traits\TraitServer;
use MbcUserProvider\Traits\TraitUser;


class MbcProvider extends AbstractProvider implements ProviderInterface, MbcUserInterface, MbcServerInterface
{
    use TraitUser;

    use TraitServer;

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


    // $guzzle == base $guzzle config : ['base_uri' => 'http://0.0.0.0/test']

//    public function __construct(Request $request, $clientId, $clientSecret, $redirectUrl, $guzzle = [])
//    {
//        parent::__construct($request, $clientId, $clientSecret, $redirectUrl, $guzzle);
//    }

    /**
     * {@inheritdoc}
     */
    protected function getAuthUrl($state)
    {
        $scopes = 'route:read route:create';

        $state = \Illuminate\Support\Str::random(40);

        $codeChallenge = strtr(rtrim(
            base64_encode(hash('sha256', $this->getCodeVerifier(), true))
            , '='), '+/', '-_');

        $data = [
            'client_id'             => 5,
            'redirect_uri'          => env('MBC_LOGIN_OAUTH_URL_CALLBACK'),
            'response_type'         => 'code',
            'scope'                 => $scopes,
            'state'                 => $state,
            'code_challenge'        => $codeChallenge,
            'code_challenge_method' => 'S256',
//             'prompt' => 'consent', // "none", "consent", or "login"
        ];

        $query = http_build_query($data);

        return $this->getHost() . '/oauth/authorize?' . $query;
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenUrl()
    {
        return $this->getHost() . '/oauth/token';
    }


    /**
     * {@inheritdoc}
     */
    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->get($this->getHost() . '/oauth/userinfo', [
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
//        $user['id'] = Arr::get($user, 'id');

        return (new User())->setRaw($user)->map([
            'id'    => Arr::get($user, 'id'),
            'name'  => Arr::get($user, 'name'),
            'email' => Arr::get($user, 'email'),
            'email_verified_at' => Arr::get($user, 'email_verified_at'),
        ]);
    }


}
