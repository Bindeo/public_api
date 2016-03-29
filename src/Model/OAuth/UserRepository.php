<?php

namespace PublicApi\Model\OAuth;

use Bindeo\Util\ApiConnection;
use League\OAuth2\Server\Entities\Interfaces\ClientEntityInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;
use Slim\Http\Request;

class UserRepository implements UserRepositoryInterface
{
    private $api;

    public function __construct(ApiConnection $api)
    {
        $this->api = $api;
    }

    /**
     * Get a user entity.
     *
     * @param string                                                          $username
     * @param string                                                          $password
     * @param string                                                          $grantType The grant type used
     * @param \League\OAuth2\Server\Entities\Interfaces\ClientEntityInterface $clientEntity
     *
     * @return \League\OAuth2\Server\Entities\Interfaces\UserEntityInterface
     */
    public function getUserEntityByUserCredentials(
        $username,
        $password,
        $grantType,
        ClientEntityInterface $clientEntity
    ) {
        // Login user against private API
        $res = $this->api->getJson('account',
            ['email' => $username, 'password' => $password, 'ip' => OAuthRegistry::getInstance()->getIp()]);
        if ($res->getError() or !$res->getNumRows() == 1) {
            return null;
        } else {
            $user = $res->getRows()[0];
            $user->setIdentifier($user->getIdUser());

            return $user;
        }
    }
}