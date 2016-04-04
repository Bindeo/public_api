<?php

namespace PublicApi\Middleware;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use PublicApi\Model\OAuth\OAuthRegistry;

class Security
{
    /**
     * Save OAuth data in OAuthRegistry
     *
     * @param Request|\Slim\Http\Request   $request
     * @param Response|\Slim\Http\Response $response
     * @param callable                     $next
     *
     * @return \Slim\Http\Response
     * @throws \Exception
     */
    public function __invoke(Request $request, Response $response, callable $next)
    {
        // Define scopes hierarchy
        $scopes = [
            'anonymous' => [],
            'factum'    => [
                'anonymous'
            ],
            'all'       => [
                'anonymous',
                'factum'
            ]
        ];

        // Define scope minimum permissions per route
        $security = [
            '/general/account-types' => [
                'GET' => [
                    'anonymous'
                ]
            ],
            '/general/media-types'   => [
                'GET' => [
                    'anonymous'
                ]
            ],
            '/general/file-types'    => [
                'GET' => [
                    'anonymous'
                ]
            ],
            '/account'               => [
                'POST'   => [
                    'anonymous'
                ],
                'PUT'    => [
                    'all'
                ],
                'DELETE' => [
                    'all'
                ]
            ],
            '/account/password'      => [
                'GET' => [
                    'anonymous'
                ],
                'PUT' => [
                    'all'
                ]
            ],
            '/account/type'          => [
                'PUT' => [
                    'all'
                ]
            ],
            '/account/token'         => [
                'GET' => [
                    'all'
                ]
            ],
            '/account/identities'    => [
                'GET' => [
                    'all'
                ],
                'PUT' => [
                    'all'
                ]
            ]
        ];

        // Check if request has enough privileges
        if (isset($security[$request->getUri()->getPath()][$request->getOriginalMethod()])) {
            if (in_array(OAuthRegistry::getInstance()->getClientRole(),
                    $security[$request->getUri()->getPath()][$request->getOriginalMethod()]) or
                array_intersect($scopes[OAuthRegistry::getInstance()->getClientRole()],
                    $security[$request->getUri()->getPath()][$request->getOriginalMethod()])
            ) {
                return $next($request, $response);
            }
        }

        // Forbidden access
        throw new \Exception('You need other access privileges', 403);
    }
}