<?php

namespace PublicApi\Model\OAuth;

use Bindeo\Util\ApiConnection;
use League\OAuth2\Server\Entities\Interfaces\ClientEntityInterface;
use League\OAuth2\Server\Entities\Interfaces\ScopeEntityInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;

class ScopeRepository implements ScopeRepositoryInterface
{
    private $api;

    public function __construct(ApiConnection $api)
    {
        $this->api = $api;
    }

    /**
     * Return information about a scope.
     *
     * @param string $identifier The scope identifier
     *
     * @return \League\OAuth2\Server\Entities\Interfaces\ScopeEntityInterface
     */
    public function getScopeEntityByIdentifier($identifier)
    {
        if (in_array($identifier, ['all', 'anonymous', 'advanced', 'factum', 'client_credentials', 'password'])) {
            $scope = new ScopeEntity();
            $scope->setIdentifier($identifier);

            return $scope;
        }
    }

    /**
     * Given a client, grant type and optional user identifier validate the set of scopes requested are valid and
     * optionally append additional scopes or remove requested scopes.
     *
     * @param ScopeEntityInterface[]                                          $scopes
     * @param string                                                          $grantType
     * @param \League\OAuth2\Server\Entities\Interfaces\ClientEntityInterface $clientEntity
     * @param null|string                                                     $userIdentifier
     *
     * @return \League\OAuth2\Server\Entities\Interfaces\ScopeEntityInterface[]
     */
    public function finalizeScopes(
        array $scopes,
        $grantType,
        ClientEntityInterface $clientEntity,
        $userIdentifier = null
    ) {
        // Set default scope
        if (count($scopes) == 0) {
            $scope = new ScopeEntity();
            $scope->setIdentifier(($clientEntity->getRole() == 'all' and !$userIdentifier) ? 'anonymous' : $clientEntity->getRole());
            $scopes[] = $scope;

            // Add grant type as scope
            $scope = new ScopeEntity();
            $scope->setIdentifier($grantType);
            $scopes[] = $scope;
        }

        return $scopes;
    }
}