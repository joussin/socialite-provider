<?php

namespace MbcUserProvider\Contracts;

interface Server
{

    public function server(array $params = []) : ?array;

    public function serverAccessToken(): string;


}