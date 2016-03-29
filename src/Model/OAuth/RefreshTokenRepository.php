<?php

namespace PublicApi\Model\OAuth;

use Bindeo\DataModel\DataModelAbstract;
use Bindeo\Util\ApiConnection;
use League\OAuth2\Server\Entities\Interfaces\RefreshTokenEntityInterface;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;

class RefreshTokenRepository implements RefreshTokenRepositoryInterface
{
    private $api;

    public function __construct(ApiConnection $api)
    {
        $this->api = $api;
    }

    /**
     * Create a new refresh token_name.
     *
     * @param \League\OAuth2\Server\Entities\Interfaces\RefreshTokenEntityInterface $refreshTokenEntity
     */
    public function persistNewRefreshToken(RefreshTokenEntityInterface $refreshTokenEntity)
    {
        // Save token in cache
        apc_store($refreshTokenEntity->getIdentifier(), $refreshTokenEntity,
            $refreshTokenEntity->getExpiryDateTime()->format('U') - (new \DateTime())->format('U'));

        // Save token in database
        $this->api->postJson('oauth_token', [
            'token'       => $refreshTokenEntity->getIdentifier(),
            'type'        => 'R',
            'expiration'  => $refreshTokenEntity->getExpiryDateTime()->format(DataModelAbstract::DATETIME_MASK),
            'accessToken' => $refreshTokenEntity->getAccessToken()->getIdentifier(),
            'ip'          => OAuthRegistry::getInstance()->getIp()
        ]);
    }

    /**
     * Revoke the refresh token.
     *
     * @param string $tokenId
     */
    public function revokeRefreshToken($tokenId)
    {
        // Delete from cache
        if (apc_exists($tokenId)) {
            apc_delete($tokenId);
        }

        // Expire in database
        $this->api->deleteJson('oauth_token', ['token' => $tokenId]);
    }

    /**
     * Check if the refresh token has been revoked.
     *
     * @param string $tokenId
     *
     * @return bool Return true if this token has been revoked
     */
    public function isRefreshTokenRevoked($tokenId)
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