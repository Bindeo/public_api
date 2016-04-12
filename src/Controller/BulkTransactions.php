<?php

namespace PublicApi\Controller;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use PublicApi\Entity\BulkType;

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
}