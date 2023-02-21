<?php

namespace MbcUserProvider\Contracts;

interface JwtToken
{
    public function parseToken(string $access_token): ?\MbcUserProvider\Entity\JwtToken;
}