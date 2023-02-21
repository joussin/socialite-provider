<?php

namespace MbcUserProvider\Two;

use MbcUserProvider\Entity\JwtToken;
use Illuminate\Support\Facades\Log;
use Lcobucci\JWT\Encoding\CannotDecodeContent;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Token\InvalidTokenStructure;
use Lcobucci\JWT\Token\Parser;
use Lcobucci\JWT\Token\UnsupportedHeaderFound;


class CommonUserProvider
{

    public function mapObjectToModel(\Laravel\Socialite\Contracts\User $user) : \App\Models\User
    {
        $userLaravel = \App\Models\User::updateOrCreate([
            'email' => $user->email,
        ], [
            'name'  => $user->name,
            'email' => $user->email,
            'email_verified_at' => $user->email_verified_at,
//            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'password' => uniqid(), // password
        ]);


//    $userLaravel->accessToken = $user->token;

        return $userLaravel;
    }


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
