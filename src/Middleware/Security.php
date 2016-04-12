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
            'advanced'  => [
                'anonymous'
            ],
            'bulk'      => [
                'anonymous'
            ],
            'factum'    => [
                'anonymous',
                'bulk'
            ],
            'all'       => [
                'anonymous',
                'advanced',
                'bulk',
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
            ],
            '/advanced/blockchain'   => [
                'POST' => [
                    'advanced'
                ],
                'GET'  => [
                    'advanced'
                ]
            ],
            '/bulk/%'                => [
                'POST' => [
                    'bulk'
                ],
                'GET'  => [
                    'bulk'
                ]
            ]
        ];

        // Check if request has enough privileges
        if (isset($security[$request->getUri()->getPath()][$request->getOriginalMethod()])) {
            // Direct url
            $registry = $security[$request->getUri()->getPath()][$request->getOriginalMethod()];
        } else {
            // Try to find a wildcard url
            $parts = explode('/', $request->getUri()->getPath());
            if (isset($security['/' . $parts[1] . '/%'][$request->getOriginalMethod()])) {
                $registry = $security['/' . $parts[1] . '/%'][$request->getOriginalMethod()];
            } else {
                $registry = null;
            }
        }

        // Look for privileges
        if (is_array($registry)) {
            if (in_array(OAuthRegistry::getInstance()->getClientRole(), $registry) or
                array_intersect($scopes[OAuthRegistry::getInstance()->getClientRole()], $registry)
            ) {
                return $next($request, $response);
            }
        }

        // Forbidden access
        throw new \Exception('You need other access privileges', 403);
    }
}