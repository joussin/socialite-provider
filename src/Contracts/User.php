<?php

namespace MbcUserProvider\Contracts;

interface User
{

    public function setHost($host);

    public function getHost(): string;

    public function getCodeVerifier(): string;

    public function setCodeVerifier(string $code_verifier);


    public function mapObjectToModel(\Laravel\Socialite\Contracts\User $user) : \App\Models\User;



    /**
     * DOIS DISPARAITRE : mettre 'code_verifier' en session
     *
     * @param $code
     * @return array
     */
    public function getTokenFields($code) : array;

}