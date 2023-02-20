<?php

namespace ApiOAuthSdk\Services;

use Illuminate\Support\Facades\Log;
use Lcobucci\JWT\Encoding\CannotDecodeContent;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Token\InvalidTokenStructure;
use Lcobucci\JWT\Token\Parser;
use Lcobucci\JWT\Token\UnsupportedHeaderFound;
use ApiOAuthSdk\Entity\JwtToken;

class OAuth2TokenService implements OAuth2TokenServiceInterface
{

    public function parseToken(string $access_token): ?JwtToken
    {
        $parser = new Parser(new JoseEncoder());

        try {
            $token = $parser->parse($access_token);

            $access_token_id = $token->claims()->get('jti');
            $client_id = $token->claims()->get('aud');
            $user_id = $token->claims()->get('sub') ?? null;
            $scopes = $token->claims()->get('scopes') ?? null;

            return new JwtToken($access_token, $token, $access_token_id, $client_id[0], $user_id, $scopes);

        } catch (CannotDecodeContent|InvalidTokenStructure|UnsupportedHeaderFound $e) {
            Log::info($e->getMessage());
        }
        return null;
    }



}
