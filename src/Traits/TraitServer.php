<?php

namespace MbcUserProvider\Traits;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;


trait TraitServer
{

    public function server(string $path, string $method = 'GET', array $params = []) : ?array
    {
        if(empty($path))
            return null;

        $token = $this->serverAccessToken();

        $methods = ['GET', 'POST', 'PUT', 'DELETE'];

        $method = in_array($method, $methods) ? strtolower($method) : 'get';

        $client = new Client([
            'base_uri' => env('MBC_SERVER_SIDE_API_URL')
        ]);

        $response = $client->$method($path, [
            RequestOptions::HEADERS => [
                'Accept'        => 'application/json',
                'Authorization' => 'Bearer ' . $token,
            ],
            //            RequestOptions::FORM_PARAMS => $params,
        ]);

        return json_decode($response->getBody(), true);
    }

    /**
     * Get the server access token
     *
     * @return array
     */
    public function serverAccessToken(): string
    {
        $fields = [
            'grant_type'    => 'client_credentials',
            'client_id'     => env('MBC_SERVER_SIDE_API_OAUTH_CLIENT_ID'), //$this->clientId,
            'client_secret' => env('MBC_SERVER_SIDE_API_OAUTH_CLIENT_SECRET'), //$this->clientSecret,
            'scopes'        => '' // route:create route:delete
        ];


        $response = $this->getHttpClient()->post($this->getTokenUrl(), [
            RequestOptions::FORM_PARAMS => $fields,
//            RequestOptions::HEADERS => [
//                'Accept'        => 'application/json'
//            ],
        ]);

        return json_decode($response->getBody(), true)['access_token'];
    }


}
