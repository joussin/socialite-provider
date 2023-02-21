<?php

namespace MbcUserProvider\Contracts;

interface Server
{

    public function server(string $path, string $method = 'GET', array $params = []) : ?array;

    public function serverAccessToken(): string;


}