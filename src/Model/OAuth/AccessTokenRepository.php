<?php

namespace PublicApi\Model\OAuth;

use Bindeo\DataModel\DataModelAbstract;
use Bindeo\Util\ApiConnection;
use League\OAuth2\Server\Entities\Interfaces\AccessTokenEntityInterface;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;

class AccessTokenRepository implements AccessTokenRepositoryInterface
{
    private $api;

    public function __construct(ApiConnection $api)
    {
        $this->api = $api;
    }

    /**
     * Persists a new access token to permanent storage.
     *
     * @param \League\OAuth2\Server\Entities\Interfaces\AccessTokenEntityInterface $accessTokenEntity
     */
    public function persistNewAccessToken(AccessTokenEntityInterface $accessTokenEntity)
    {
        // Save token in cache
        apc_store($accessTokenEntity->getIdentifier(), $accessTokenEntity,
            $accessTokenEntity->getExpiryDateTime()->format('U') - (new \DateTime())->format('U'));

        // Save token in database
        $this->api->postJson('oauth_token', [
            'token'      => $accessTokenEntity->getIdentifier(),
            'type'       => 'A',
            'expiration' => $accessTokenEntity->getExpiryDateTime()->format(DataModelAbstract::DATETIME_MASK),
            'idClient'   => $accessTokenEntity->getClient()->getIdentifier(),
            'idUser'     => $accessTokenEntity->getUserIdentifier(),
            'ip'         => OAuthRegistry::getInstance()->getIp()
        ]);
    }

    /**
     * Revoke an access token.
     *
     * @param string $tokenId
     */
    public function revokeAccessToken($tokenId)
    {
        // Delete from cache
        if (apc_exists($tokenId)) {
            apc_delete($tokenId);
        }

        // Expire in database
        $this->api->deleteJson('oauth_token', ['token' => $tokenId]);
    }

    /**
     * Check if the access token has been revoked.
     *
     * @param string $tokenId
     *
     * @return bool Return true if this token has been revoked
     */
    public function isAccessTokenRevoked($tokenId)
    {
        // Check in cache
        $revoked = !apc_exists($tokenId);

        if ($revoked) {
            // If it isn't in cache, we look for it in database
            $res = $this->api->getJson('oauth_token', ['token' => $tokenId]);
            if ($res->getNumRows() == 1) {
                $revoked = false;
                // Store it in cache
                apc_store($tokenId, $tokenId,
                    $res->getRows()[0]->getExpiration()->format('U') - (new \DateTime())->format('U'));
            }
        }

        return $revoked;
    }
}