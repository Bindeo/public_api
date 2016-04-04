<?php

namespace PublicApi\Model\OAuth;

use Bindeo\Util\ApiConnection;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use PublicApi\Entity\OAuthClient;

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
            /**
             * @var $client OAuthClient
             */
            $client = $res->getRows()[0];
            $client->setIdentifier($client->getIdClient());

            // Check valid IP for restringed clients
            if ($client->getAllowedIps()) {
                $ips = explode(',', $client->getAllowedIps());
                $clientIp = OAuthRegistry::getInstance()->getIp();

                if ($clientIp != '127.0.0.1' and !in_array($clientIp, $ips)) {
                    return null;
                }
            }

            return $client;
        }
    }
}