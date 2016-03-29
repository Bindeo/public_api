<?php

namespace PublicApi\Model\OAuth;

/**
 * Store OAuth data
 */
class OAuthRegistry
{
    const GRANT_CREDENTIALS = 'client_credentials';
    const GRANT_PASSWORD    = 'password';

    const CLIENT_ROLE_FULL     = 'full';
    const CLIENT_ROLE_EXTERNAL = 'external';
    const CLIENT_ROLE_SANDBOX  = 'sandbox';

    const ROLE_ANONYMOUS = 0;
    const ROLE_ADMIN     = 1;
    const ROLE_USER      = 2;
    const ROLE_VIP       = 3;

    protected static $me;
    protected        $token;
    protected        $grantType;
    protected        $clientId;
    protected        $clientRole;
    protected        $userRole;
    protected        $userId;
    protected        $ip;

    /**
     * Singleton constructor
     */
    protected function __construct() { }

    /**
     * Singleton getInstance method
     * @return OAuthRegistry
     */
    public static function getInstance()
    {
        if (self::$me === null) {
            self::$me = new OAuthRegistry();
        }

        return self::$me;
    }

    // We prevent that objects of this class can be cloned or deserialized to ensure the singleton pattern
    public function __clone() { }

    public function __wakeup() { }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param mixed $token
     *
     * @return OAuthRegistry
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getGrantType()
    {
        return $this->grantType;
    }

    /**
     * @param mixed $grantType
     *
     * @return OAuthRegistry
     */
    public function setGrantType($grantType)
    {
        $this->grantType = $grantType;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @param mixed $clientId
     *
     * @return OAuthRegistry
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getClientRole()
    {
        return $this->clientRole;
    }

    /**
     * @param mixed $clientRole
     *
     * @return OAuthRegistry
     */
    public function setClientRole($clientRole)
    {
        $this->clientRole = $clientRole;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUserRole()
    {
        return $this->userRole;
    }

    /**
     * @param mixed $userRole
     *
     * @return OAuthRegistry
     */
    public function setUserRole($userRole)
    {
        $this->userRole = $userRole;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param mixed $userId
     *
*@return OAuthRegistry
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @param mixed $ip
     *
     * @return OAuthRegistry
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }
}