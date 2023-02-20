<?php

namespace ApiOAuthSdk\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class OAuth2Controller extends Controller
{

    public function __construct()
    {
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
