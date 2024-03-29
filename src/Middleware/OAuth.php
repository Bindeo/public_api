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
        $scopes = $request->getAttribute('oauth_scopes');

        OAuthRegistry::getInstance()
                     ->setToken($request->getAttribute('oauth_access_token_id'))
                     ->setGrantType(isset($scopes[1]) and
                                    $scopes[1] == OAuthRegistry::GRANT_CREDENTIALS ? OAuthRegistry::GRANT_CREDENTIALS
                                        : OAuthRegistry::GRANT_PASSWORD)
                     ->setClientId($request->getAttribute('oauth_client_id'))
                     ->setClientRole($request->getAttribute('oauth_scopes')[0])
                     ->setUserId($request->getAttribute('oauth_scopes')[0] != 'anonymous'
                         ? $request->getAttribute('oauth_user_id') : null);

        return $next($request, $response);
    }
}