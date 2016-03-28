<?php

namespace PublicApi\Model\OAuth;

use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;

class Server
{
    private $keys;
    private $tokenRepository;
    private $refreshRepository;
    private $clientRepository;
    private $userRepository;
    private $scopeRepository;

    public function __construct(
        array $keys,
        AccessTokenRepositoryInterface $tokenRepo,
        RefreshTokenRepositoryInterface $refreshRepo,
        ClientRepositoryInterface $clientRepo,
        UserRepositoryInterface $userRepo,
        ScopeRepositoryInterface $scopeRepo
    ) {
        // Private and public openssh keys
        $this->keys = $keys;

        // Init our repositories
        $this->tokenRepository = $tokenRepo;
        $this->refreshRepository = $refreshRepo;
        $this->clientRepository = $clientRepo;
        $this->userRepository = $userRepo;
        $this->scopeRepository = $scopeRepo;
    }

    public function getServer()
    {
        // Setup the authorization server
        $server = new \League\OAuth2\Server\Server($this->clientRepository, $this->tokenRepository,
            $this->scopeRepository, $this->keys['private'], $this->keys['public']);

        // Enable the password grant on the server with an access token TTL of 1 hour
        $server->enableGrantType(new \League\OAuth2\Server\Grant\PasswordGrant($this->userRepository,
            $this->refreshRepository), new \DateInterval('PT1H'));

        // Enable the client credentials grant on the server with a token TTL of 1 hour
        $server->enableGrantType(new \League\OAuth2\Server\Grant\ClientCredentialsGrant(), new \DateInterval('PT1H'));

        return $server;
    }
}