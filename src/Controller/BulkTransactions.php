<?php

namespace PublicApi\Controller;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use PublicApi\Entity\BulkTransaction;
use PublicApi\Entity\BulkType;
use PublicApi\Model\OAuth\OAuthRegistry;

class BulkTransactions
{
    private $model;

    public function __construct(\PublicApi\Model\BulkTransactions $model)
    {
        $this->model = $model;
    }

    /**
     * Get the bulk types list of a client
     *
     * @param Request|\Slim\Http\Request   $request
     * @param Response|\Slim\Http\Response $response
     * @param array                        $args [optional]
     *
     * @return \Slim\Http\Response
     * @throws \Exception
     */
    public function bulkTypes(Request $request, Response $response, $args)
    {
        $data = $this->model->bulkTypes();

        $res = ['data' => $data->toArray('bulk_type'), 'total_pages' => 1];

        return $response->withJson($res, 200);
    }

    /**
     * Create or open new bulk transaction
     *
     * @param Request|\Slim\Http\Request   $request
     * @param Response|\Slim\Http\Response $response
     * @param array                        $args [optional]
     *
     * @return \Slim\Http\Response
     */
    public function createBulk(Request $request, Response $response, $args)
    {
        // Call model
        $res = $this->model->createBulk($request->getParams());

        return $response->withJson($res->toArray(), 201);
    }

    /**
     * Get information about bulk transaction
     *
     * @param Request|\Slim\Http\Request   $request
     * @param Response|\Slim\Http\Response $response
     * @param array                        $args [optional]
     *
     * @return \Slim\Http\Response
     * @throws \Exception
     */
    public function getBulk(Request $request, Response $response, $args)
    {
        // Instantiate BulkTransaction
        $bulk = (new BulkTransaction())->setExternalId($args['id']);
        if (OAuthRegistry::getInstance()->getGrantType() == OAuthRegistry::GRANT_CREDENTIALS) {
            $bulk->setClientType('C')->setIdClient(OAuthRegistry::getInstance()->getClientId());
        } else {
            $bulk->setClientType('U')->setIdClient(OAuthRegistry::getInstance()->getUserId());
        }

        // Get requested info about the bulk transaction
        $res = $this->model->getBulk($bulk, $request->getParam('mode'));
        $res = ['data' => ['type' => 'bulk_transaction', 'attributes' => $res]];

        return $response->withJson($res, 200);
    }

    /**
     * Add an item to an opened bulk transaction
     *
     * @param Request|\Slim\Http\Request   $request
     * @param Response|\Slim\Http\Response $response
     * @param array                        $args [optional]
     *
     * @return \Slim\Http\Response
     */
    public function addItem(Request $request, Response $response, $args)
    {
        // Call model
        $res = $this->model->addItem($args['id'], $request->getParams());
        $res = ['data' => ['type' => 'bulk_transaction', 'attributes' => $res]];

        return $response->withJson($res, 201);
    }

    /**
     * Delete an opened bulk transaction
     *
     * @param Request|\Slim\Http\Request   $request
     * @param Response|\Slim\Http\Response $response
     * @param array                        $args [optional]
     *
     * @return \Slim\Http\Response
     * @throws \Exception
     */
    public function deleteBulk(Request $request, Response $response, $args)
    {
        // Instantiate BulkTransaction
        $bulk = (new BulkTransaction())->setExternalId($args['id']);
        if (OAuthRegistry::getInstance()->getGrantType() == OAuthRegistry::GRANT_CREDENTIALS) {
            $bulk->setClientType('C')->setIdClient(OAuthRegistry::getInstance()->getClientId());
        } else {
            $bulk->setClientType('U')->setIdClient(OAuthRegistry::getInstance()->getUserId());
        }

        // Delete the transaction if is still opened
        $this->model->deleteBulk($bulk);

        return $response->withJson('', 204);
    }

    /**
     * Close an opened bulk transaction
     *
     * @param Request|\Slim\Http\Request   $request
     * @param Response|\Slim\Http\Response $response
     * @param array                        $args [optional]
     *
     * @return \Slim\Http\Response
     * @throws \Exception
     */
    public function closeBulk(Request $request, Response $response, $args)
    {
        // Instantiate BulkTransaction
        $bulk = (new BulkTransaction())->setExternalId($args['id'])->setIp(OAuthRegistry::getInstance()->getIp());
        if (OAuthRegistry::getInstance()->getGrantType() == OAuthRegistry::GRANT_CREDENTIALS) {
            $bulk->setClientType('C')->setIdClient(OAuthRegistry::getInstance()->getClientId());
        } else {
            $bulk->setClientType('U')->setIdClient(OAuthRegistry::getInstance()->getUserId());
        }

        // Close the bulk transaction
        $res = $this->model->closeBulk($bulk);
        $res = ['data' => ['type' => 'bulk_transaction', 'attributes' => $res]];

        return $response->withJson($res, 200);
    }
}