<?php

namespace MbcUserProvider\Utils\Entity;

use Lcobucci\JWT\Token as TokenInterface;

class JwtToken implements \JsonSerializable
{
    /**
     * @var string
     */
    protected string $access_token_raw;

    /**
     * @var TokenInterface
     */
    protected $token;


    protected string $access_token_id;

    protected int $client_id;
    protected ?int $user_id;
    protected ?array $scopes;


    /**
     * @param string $access_token_raw
     * @param TokenInterface $token
     * @param string $access_token_id
     * @param int $client_id
     * @param int|null $user_id
     * @param array|null $scopes
     */
    public function __construct(string $access_token_raw, TokenInterface $token, string $access_token_id, int $client_id, ?int $user_id, ?array $scopes)
    {
        $this->access_token_raw = $access_token_raw;
        $this->token = $token;
        $this->access_token_id = $access_token_id;
        $this->client_id = $client_id;
        $this->user_id = $user_id;
        $this->scopes = $scopes;
    }

    public function jsonSerialize()
    {
        $array = get_object_vars($this);

        unset($array['access_token_raw']);
        unset($array['token']);

        return json_encode($array);
    }

    /**
     * @return string
     */
    public function getAccessTokenRaw(): string
    {
        return $this->access_token_raw;
    }

    /**
     * @return TokenInterface
     */
    public function getToken(): TokenInterface
    {
        return $this->token;
    }

    /**
     * @return int
     */
    public function getAccessTokenId(): int
    {
        return $this->access_token_id;
    }

    /**
     * @return int
     */
    public function getClientId(): int
    {
        return $this->client_id;
    }

    /**
     * @return int|null
     */
    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    /**
     * @return array|null
     */
    public function getScopes(): ?array
    {
        return $this->scopes;
    }


}
