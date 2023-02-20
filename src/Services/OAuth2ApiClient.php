<?php

namespace ApiOAuthSdk\Services;

use GuzzleHttp\ClientInterface;
use Psr\Http\Message\ResponseInterface;

class OAuth2ApiClient implements OAuth2ApiClientInterface
{

    protected $code_verifier;

    protected $client;
    protected $oauth_api_url;
    protected $api_content_login_callback_url;

    public const CV = 'P6oelECwFb5dACCIeafOu6DO2gfBMsOupeap1CiWRg3U3n9PE2tzrsY93xsXonyGkKFYqLexiKVdQ8wPsaRsdrKloR7VvxJ9sIKDEsKJWioeex7kB8NQjucObr2mPjs2';

    public function __construct(ClientInterface $client, string $oauth_api_url, string $api_content_login_callback_url)
    {
        //$this->code_verifier = \Illuminate\Support\Str::random(128);
        $this->code_verifier = "P6oelECwFb5dACCIeafOu6DO2gfBMsOupeap1CiWRg3U3n9PE2tzrsY93xsXonyGkKFYqLexiKVdQ8wPsaRsdrKloR7VvxJ9sIKDEsKJWioeex7kB8NQjucObr2mPjs2";

        $this->client = $client;
        $this->oauth_api_url = $oauth_api_url;
        $this->api_content_login_callback_url = $api_content_login_callback_url;



        request()->session()->put('code_verifier', $this->code_verifier);

    }

    private function parseResponse(ResponseInterface $response): ?array
    {
        try {
            $responseContent = $response->getBody()->getContents();
            $responseContentArray = json_decode($responseContent, true);

            return $responseContentArray;
        } catch (\Exception $exception) {
            return null;
        }
    }


    public function getLoginUrl(): string
    {
        $scopes = 'route:read route:create';

        $state = \Illuminate\Support\Str::random(40);

        $codeChallenge = strtr(rtrim(
//            base64_encode(hash('sha256', $this->code_verifier, true))
            base64_encode(hash('sha256', self::CV, true))
            , '='), '+/', '-_');

        $data = [
            'client_id'             => 5,
            'redirect_uri'          => $this->api_content_login_callback_url,
            'response_type'         => 'code',
            'scope'                 => $scopes,
            'state'                 => $state,
            'code_challenge'        => $codeChallenge,
            'code_challenge_method' => 'S256',
//             'prompt' => 'consent', // "none", "consent", or "login"
        ];

        $query = http_build_query($data);

//        dd($data);
        return $this->oauth_api_url . '/oauth/authorize?' . $query;
    }


    public function getToken(string $code): string
    {
        $data = [
            'grant_type'    => 'authorization_code',
            'client_id'     => 5,
            'redirect_uri'  => $this->api_content_login_callback_url,
//            'code_verifier' => $this->code_verifier,
            'code_verifier' => self::CV,
            'code'          => $code,
        ];

        $response = $this->client->post('/oauth/token', [
            'form_params' => $data
        ]);

        $result = $this->parseResponse($response);

        $access_token = $result['access_token'];

        return $access_token;
    }

}
