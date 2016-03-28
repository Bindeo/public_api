<?php

namespace PublicApi\Model\General;

/**
 * Store OAuth data
 */
class OAuthRegistry
{
    const GRANT_NONE        = 'no_auth';
    const GRANT_CREDENTIALS = 'client_credentials';
    const GRANT_AUTH_CODE   = 'authorization_code';

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
    protected        $user;

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

    // Impedimos que los objetos de esta clase puedan ser clonados o deserializados, para asegurar el singleton
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
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     *
     * @return OAuthRegistry
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }
}