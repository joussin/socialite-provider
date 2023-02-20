<?php

namespace ApiOAuthSdk\Services;

use ApiOAuthSdk\Entity\JwtToken;

interface OAuth2TokenServiceInterface
{

    public function parseToken(string $access_token) : ?JwtToken;
}
