<?php

namespace PublicApi\Model\OAuth;

use Bindeo\Util\ApiConnection;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;

class ClientRepository implements ClientRepositoryInterface
{
    private $api;

    public function __construct(ApiConnection $api)
    {
        $this->api = $api;
    }

    /**
     * Get a client.
     *
     * @param string      $clientIdentifier The client's identifier
     * @param string      $grantType        The grant type used
     * @param null|string $clientSecret     The client's secret (if sent)
     *
     * @return \League\OAuth2\Server\Entities\Interfaces\ClientEntityInterface
     */
    public function getClientEntity($clientIdentifier, $grantType, $clientSecret = null)
    {
        // Look for the client into the api
        $res = $this->api->getJson('oauth_clients', ['name' => $clientIdentifier, 'secret' => $clientSecret]);

        if ($res->getError() or !$res->getNumRows() == 1) {
            return null;
        } else {
            $client = $res->getRows()[0];
            $client->setIdentifier($client->getName());
            return $client;
        }
    }
}