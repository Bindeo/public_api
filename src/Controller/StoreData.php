<?php

namespace PublicApi\Controller;

use Bindeo\Util\ApiConnection;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class StoreData
{
    private $api;

    public function __construct(ApiConnection $api)
    {
        $this->api = $api;
    }

    /**
     * Post data into blockchain
     *
     * @param Request|\Slim\Http\Request   $request
     * @param Response|\Slim\Http\Response $response
     * @param array                        $args [optional]
     *
     * @return \Slim\Http\Response
     * @throws \Exception
     */
    public function postBlockchainData(Request $request, Response $response, $args)
    {
        $data = $this->api->postJson('advanced_blockchain', ['data' => $request->getParam('data')]);

        if ($data->getError()) {
            throw new \Exception($data->getError()['message'], $data->getError()['code']);
        }

        return $response->withJson($data->getRows()[0], 201);
    }

    /**
     * Get data from blockchain
     *
     * @param Request|\Slim\Http\Request   $request
     * @param Response|\Slim\Http\Response $response
     * @param array                        $args [optional]
     *
     * @return \Slim\Http\Response
     * @throws \Exception
     */
    public function getBlockchainData(Request $request, Response $response, $args)
    {
        $data = $this->api->getJson('advanced_blockchain',
            ['mode' => $request->getParam('mode'), 'txid' => $request->getParam('txid')]);

        if ($data->getError()) {
            throw new \Exception($data->getError()['message'], $data->getError()['code']);
        }

        return $response->withJson($data->getRows()[0], 200);
    }
}