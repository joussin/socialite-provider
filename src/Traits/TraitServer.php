<?php

namespace MbcUserProvider\Traits;

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

        $response = $this->getHttpClient()->$method($path, [
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
            'client_id'     => $this->clientId,
            'client_secret' => $this->clientSecret,
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
