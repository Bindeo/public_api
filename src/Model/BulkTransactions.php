<?php

namespace PublicApi\Model;

use Bindeo\DataModel\Exceptions;
use Bindeo\Util\ApiConnection;
use PublicApi\Entity\BulkTransaction;
use PublicApi\Entity\BulkType;
use PublicApi\Model\OAuth\OAuthRegistry;

/**
 * Class BulkTransactions to manage BulkTransactions controller functionality
 * @package PublicApi\Model
 */
class BulkTransactions
{
    private $api;

    public function __construct(ApiConnection $api)
    {
        $this->api = $api;
    }

    /**
     * Get the bulk types list of a client
     * @return \Bindeo\DataModel\ClientResultSetAbstract
     * @throws \Exception
     */
    public function bulkTypes()
    {
        $data = $this->api->getJson('bulk_types',
            (new BulkType())->setIdClient(OAuthRegistry::getInstance()->getClientId())->setClientType('C')->toArray());

        if ($data->getError()) {
            throw new \Exception($data->getError()['message'], $data->getError()['code']);
        }

        return $data;
    }

    /**
     * Create or open new bulk transaction
     *
     * @param array $params
     *
     * @return BulkTransaction
     * @throws \Exception
     */
    public function createBulk(array $params)
    {
        // Get bulk type
        $result = $this->api->getJson('bulk_type',
            (new BulkType($params))->setIdClient(OAuthRegistry::getInstance()->getClientId())
                                   ->setClientType('C')
                                   ->toArray());

        if ($result->getError()) {
            throw new \Exception($result->getError()['message'], $result->getError()['code']);
        }
        if ($result->getNumRows() == 0) {
            throw new \Exception(Exceptions::NON_EXISTENT, 409);
        }

        // Parse params
        $data = (new BulkTransaction($params))->setIdClient(OAuthRegistry::getInstance()->getClientId())
                                              ->setClientType('C')
                                              ->setIp(OAuthRegistry::getInstance()->getIp())
                                              ->toArray();
        $data['mode'] = isset($params['mode']) ? $params['mode'] : null;

        // With bulk type we need to obtain params associated to the structure
        $bulkInfo = $result->getRows()[0]->getBulkInfo(true);
        foreach ($bulkInfo['fields'] as $field) {
            if (isset($params[$field])) {
                $data[$field] = $params[$field];
            }
        }

        $result = $this->api->postJson('bulk_transaction', $data);

        if ($result->getError()) {
            throw new \Exception($result->getError()['message'], $result->getError()['code']);
        }

        // Return only necessary info
        return (new BulkTransaction())->setExternalId($result->getRows()[0]->getExternalId())
                                      ->setType($result->getRows()[0]->getType());
    }
}