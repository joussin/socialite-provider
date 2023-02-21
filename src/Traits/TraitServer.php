<?php

namespace MbcUserProvider\Traits;

use GuzzleHttp\RequestOptions;


trait TraitServer
{

    public function server(array $params = []) : ?array
    {
        $token = $this->serverAccessToken();

        $response = $this->getHttpClient()->get($this->getTokenUrl(), [
//            RequestOptions::FORM_PARAMS => $params,
            RequestOptions::HEADERS => [
                'Accept'        => 'application/json',
                'Authorization' => 'Bearer ' . $token,
            ],
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
