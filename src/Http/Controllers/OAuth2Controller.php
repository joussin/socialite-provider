<?php

namespace ApiOAuthSdk\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use ApiOAuthSdk\Services\OAuth2ApiClientInterface;
use ApiOAuthSdk\Services\OAuth2TokenServiceInterface;


class OAuth2Controller extends Controller
{

    protected $auth2TokenService;

    protected $auth2ApiClient;

    public function __construct(OAuth2TokenServiceInterface $auth2TokenService, OAuth2ApiClientInterface $auth2ApiClient)
    {
        $this->auth2TokenService = $auth2TokenService;
        $this->auth2ApiClient = $auth2ApiClient;
    }

    public function login(Request $request)
    {
        return redirect($this->auth2ApiClient->getLoginUrl());
    }


    public function loginCallback(Request $request)
    {
        $access_token = $this->auth2ApiClient->getToken($request->code);

        $jwtToken = $this->auth2TokenService->parseToken($access_token);

        return [
            'access_token' => $access_token,
            'jwtToken'     => $jwtToken,
        ];
    }


}
