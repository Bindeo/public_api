<?php
// Application middleware
// OAuth
if ($app->getContainer()->request->getUri()->getPath() != '/access_token') {
    $app->add('PublicApi\Middleware\OAuth');
}

// Save current ip in session
$app->add(function ($request, $response, $next) use ($app) {
    $_SESSION['ip_address'] = $request->getAttribute('ip_address');
    return $next($request, $response);
});

// Client ip
$app->add(new RKA\Middleware\IpAddress(true, ['10.0.0.1', '10.0.0.2']));