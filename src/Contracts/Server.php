<?php

namespace MbcUserProvider\Contracts;

use MbcUserProvider\Entity\JwtToken;

interface Server
{

    public function server(array $params = []) : ?array;

    public function serverAccessToken(): string;


}