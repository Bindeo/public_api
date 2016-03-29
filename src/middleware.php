<?php
// Application middleware
// OAuth2
if ($app->getContainer()->request->getUri()->getPath() != '/access_token') {
    // Save OAuth data in registry
    $app->add(new PublicApi\Middleware\OAuth());
    // Check OAuth authorization
    $app->add('PublicApi\Middleware\OAuth');
}

// Save current ip in OAuthRegistry
$app->add(function ($request, $response, $next) use ($app) {
    \PublicApi\Model\OAuth\OAuthRegistry::getInstance()->setIp($request->getAttribute('ip_address'));
    return $next($request, $response);
});

// Client ip
$app->add(new RKA\Middleware\IpAddress(true, ['10.0.0.1', '10.0.0.2']));