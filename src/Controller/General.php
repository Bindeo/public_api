<?php

namespace PublicApi\Controller;

use Bindeo\Util\ApiConnection;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class General
{
    private $api;

    public function __construct(ApiConnection $api)
    {
        $this->api = $api;
    }

    /**
     * Get the account types list by language
     *
     * @param Request|\Slim\Http\Request   $request
     * @param Response|\Slim\Http\Response $response
     * @param array                        $args [optional]
     *
     * @return \Slim\Http\Response
     * @throws \Exception
     */
    public function accountTypes(Request $request, Response $response, $args)
    {
        $data = $this->api->getJson('general_account_types', ['locale' => $request->getParam('locale')]);

        if ($data->getError()) {
            throw new \Exception($data->getError()['message'], $data->getError()['code']);
        }

        $res = ['data' => $data->toArray('account_type'), 'total_pages' => 1];

        return $response->withJson($res, 200);
    }

    /**
     * Get the media types list by language
     *
     * @param Request|\Slim\Http\Request   $request
     * @param Response|\Slim\Http\Response $response
     * @param array                        $args [optional]
     *
     * @return \Slim\Http\Response
     * @throws \Exception
     */
    public function mediaTypes(Request $request, Response $response, $args)
    {
        $data = $this->api->getJson('general_media_types', ['locale' => $request->getParam('locale')]);

        if ($data->getError()) {
            throw new \Exception($data->getError()['message'], $data->getError()['code']);
        }

        $res = ['data' => $data->toArray('media_type'), 'total_pages' => 1];

        return $response->withJson($res, 200);
    }
}