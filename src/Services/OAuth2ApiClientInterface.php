<?php

namespace ApiOAuthSdk\Services;

interface OAuth2ApiClientInterface
{
    public function getLoginUrl() : string;

    public function getToken(string $code) : string;
}
