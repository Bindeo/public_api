<?php

namespace PublicApi\Model;

use Bindeo\DataModel\BulkItemInterface;
use Bindeo\DataModel\Exceptions;
use Bindeo\Util\ApiConnection;
use PublicApi\Entity\BulkEvent;
use PublicApi\Entity\BulkFile;
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
        $result = $this->api->getJson('bulk_types',
            (new BulkType())->setIdClient(OAuthRegistry::getInstance()->getClientId())->setClientType('C')->toArray());

        if ($result->getError()) {
            throw new \Exception($result->getError()['message'], $result->getError()['code']);
        }

        return $result;
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
        $bulkType = new BulkType($params);
        if (OAuthRegistry::getInstance()->getGrantType() == OAuthRegistry::GRANT_CREDENTIALS) {
            $bulkType->setClientType('C')->setIdClient(OAuthRegistry::getInstance()->getClientId());
        } else {
            $bulkType->setClientType('U')->setIdClient(OAuthRegistry::getInstance()->getUserId());
        }

        // Get bulk type from api
        $result = $this->api->getJson('bulk_type', $bulkType->toArray());

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

    /**
     * Get information about bulk transaction
     *
     * @param BulkTransaction $bulk
     *
     * @return array
     * @throws \Exception
     */
    public function getBulk(BulkTransaction $bulk, $mode)
    {
        // Check requested mode
        $mode = trim($mode);
        if (!in_array($mode, ['status', 'hash', 'structure'])) {
            throw new \Exception(Exceptions::MISSING_FIELDS, 400);
        }

        // Get the bulk transaction
        $result = $this->api->getJson('bulk_transaction', $bulk->toArray());

        if ($result->getError()) {
            throw new \Exception($result->getError()['message'], $result->getError()['code']);
        }

        // Prepared data
        if ($result->getNumRows() == 1) {
            /** @var BulkTransaction $bulk */
            $bulk = (new BulkTransaction())->setExternalId($result->getRows()[0]->getExternalId())
                                           ->setType($result->getRows()[0]->getType());

            // Return only necessary data
            if ($mode == 'status') {
                $bulk->setNumItems($result->getRows()[0]->getNumItems())->setClosed($result->getRows()[0]->getClosed());

                if ($bulk->getClosed()) {
                    $bulk->setTransaction($result->getRows()[0]->getTransaction())
                         ->setConfirmed($result->getRows()[0]->getConfirmed());
                }
            } elseif ($mode == 'hash') {
                $bulk->setHash($result->getRows()[0]->getHash());
            } elseif ($mode == 'structure') {
                $bulk->setStructure($result->getRows()[0]->getStructure());
            }

            return $bulk->toArray();
        } else {
            return [];
        }
    }

    /**
     * Add an item to an opened bulk transaction
     *
     * @param int   $idBulk
     * @param array $params
     *
     * @return array
     * @throws \Exception
     */
    public function addItem($idBulk, array $params)
    {
        // Check type param
        if (!isset($params['type']) or !in_array($params['type'], ['event', 'file'])) {
            throw new \Exception(Exceptions::MISSING_FIELDS, 400);
        }

        // Instantiate bulk item type
        if ($params['type'] == 'event') {
            $item = (new BulkEvent())->setName(isset($params['name']) ? $params['name'] : null)
                                     ->setTimestamp(isset($params['timestamp']) ? $params['timestamp'] : null)
                                     ->setData(isset($params['data']) ? $params['data'] : null);
        } else {
            $item = (new BulkFile());
        }

        // Add common data for items
        /** @var BulkItemInterface $item */
        $item->setBulkExternalId($idBulk)->setIp(OAuthRegistry::getInstance()->getIp());
        if (OAuthRegistry::getInstance()->getGrantType() == OAuthRegistry::GRANT_CREDENTIALS) {
            $item->setClientType('C')->setIdClient(OAuthRegistry::getInstance()->getClientId());
        } else {
            $item->setClientType('U')->setIdClient(OAuthRegistry::getInstance()->getUserId());
        }

        $data = $item->toArray();
        $data['type'] = $params['type'];

        // Add item to bulk transaction
        $result = $this->api->postJson('bulk_item', $data);

        if ($result->getError()) {
            throw new \Exception($result->getError()['message'], $result->getError()['code']);
        }

        // Return only necessary info
        return (new BulkTransaction())->setExternalId($result->getRows()[0]->getExternalId())
                                      ->setType($result->getRows()[0]->getType())
                                      ->setNumItems($result->getRows()[0]->getNumItems())
                                      ->setStructure($result->getRows()[0]->getStructure())
                                      ->setHash($result->getRows()[0]->getHash())
                                      ->toArray();
    }

    /**
     * Delete an opened bulk transaction
     *
     * @param BulkTransaction $bulk
     *
     * @return array
     * @throws \Exception
     */
    public function deleteBulk(BulkTransaction $bulk)
    {
        // Get the bulk transaction
        $result = $this->api->deleteJson('bulk_transaction', $bulk->toArray());

        if ($result->getError()) {
            throw new \Exception($result->getError()['message'], $result->getError()['code']);
        }
    }

    /**
     * Close an opened bulk transaction
     *
     * @param BulkTransaction $bulk
     *
     * @return array
     * @throws \Exception
     */
    public function closeBulk(BulkTransaction $bulk)
    {
        // Get the bulk transaction
        $result = $this->api->putJson('bulk_transaction', $bulk->toArray());

        if ($result->getError()) {
            throw new \Exception($result->getError()['message'], $result->getError()['code']);
        }

        /** @var BulkTransaction $bulk */
        $bulk = $result->getRows()[0];

        // Return only necessary info
        return $bulk->setIdBulkTransaction(null)
                    ->setIp(null)
                    ->setElementsType(null)
                    ->setClientType(null)
                    ->setIdClient(null)
                    ->setStatus(null)
                    ->toArray();
    }
}