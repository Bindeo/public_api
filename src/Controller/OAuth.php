<?php

namespace PublicApi\Controller;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class OAuth
{
    private $server;

    public function __construct(\League\OAuth2\Server\Server $server)
    {
        $this->server = $server;
    }

    /**
     * Get an access token
     *
     * @param Request|\Slim\Http\Request   $request
     * @param Response|\Slim\Http\Response $response
     * @param array                        $args [optional]
     *
     * @return \Slim\Http\Response
     * @throws \Exception
     */
    public function token(Request $request, Response $response, $args)
    {
        // Try to respond to the request
        try {
            return $this->server->respondToRequest($request, $response);
        } catch (\League\OAuth2\Server\Exception\OAuthServerException $exception) {
            return $exception->generateHttpResponse($response);
        } catch (\Exception $exception) {
            $body = new \Slim\Http\Stream('php://temp', 'r+');
            $body->write($exception->getMessage());

            return $response->withStatus(500)->withBody($body);
        }
    }
}