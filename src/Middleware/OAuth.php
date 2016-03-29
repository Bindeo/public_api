<?php

namespace PublicApi\Middleware;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use PublicApi\Model\OAuth\OAuthRegistry;

class OAuth
{
    /**
     * Save OAuth data in OAuthRegistry
     *
     * @param Request|\Slim\Http\Request   $request
     * @param Response|\Slim\Http\Response $response
     * @param callable                     $next
     *
     * @return \Slim\Http\Response
     */
    public function __invoke(Request $request, Response $response, callable $next)
    {
        OAuthRegistry::getInstance()
                     ->setToken($request->getAttribute('oauth_access_token_id'))
                     ->setGrantType($request->getAttribute('oauth_user_id') ? OAuthRegistry::GRANT_PASSWORD
                         : OAuthRegistry::GRANT_CREDENTIALS)
                     ->setClientId($request->getAttribute('oauth_client_id'))
                     ->setClientRole($request->getAttribute('oauth_scopes')[0])
                     ->setUserId($request->getAttribute('oauth_user_id'));

        return $next($request, $response);
    }
}