<?php
// DIC configuration

$container = $app->getContainer();

// monolog
$container['logger'] = function (Slim\Container $c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], Monolog\Logger::DEBUG));

    return $logger;
};

// Private API connection
$container['Bindeo\Util\ApiConnection'] = function (Slim\Container $c) {
    return new \Bindeo\Util\ApiConnection($c->get('settings')['api']['url'], $c->get('settings')['api']['token'],
        'api');
};

// OAuth2
$container['PublicApi\Model\OAuth\AccessTokenRepository'] = function (Slim\Container $c) {
    return new PublicApi\Model\OAuth\AccessTokenRepository($c->get('Bindeo\Util\ApiConnection'));
};

$container['PublicApi\Model\OAuth\RefreshTokenRepository'] = function (Slim\Container $c) {
    return new PublicApi\Model\OAuth\RefreshTokenRepository($c->get('Bindeo\Util\ApiConnection'));
};

$container['PublicApi\Model\OAuth\UserRepository'] = function (Slim\Container $c) {
    return new PublicApi\Model\OAuth\UserRepository($c->get('Bindeo\Util\ApiConnection'));
};

$container['PublicApi\Model\OAuth\ClientRepository'] = function (Slim\Container $c) {
    return new PublicApi\Model\OAuth\ClientRepository($c->get('Bindeo\Util\ApiConnection'));
};

$container['PublicApi\Model\OAuth\ScopeRepository'] = function (Slim\Container $c) {
    return new PublicApi\Model\OAuth\ScopeRepository($c->get('Bindeo\Util\ApiConnection'));
};

$container['PublicApi\Model\OAuth\Server'] = function (Slim\Container $c) {
    return (new PublicApi\Model\OAuth\Server($c->get('settings')['oauth']['keys'],
        $c->get('PublicApi\Model\OAuth\AccessTokenRepository'), $c->get('PublicApi\Model\OAuth\RefreshTokenRepository'),
        $c->get('PublicApi\Model\OAuth\ClientRepository'), $c->get('PublicApi\Model\OAuth\UserRepository'),
        $c->get('PublicApi\Model\OAuth\ScopeRepository')))->getServer();
};

$container['PublicApi\Middleware\OAuth'] = function (Slim\Container $c) {
    return new \League\OAuth2\Server\Middleware\ResourceServerMiddleware($c->get('PublicApi\Model\OAuth\Server'));
};

// Models
$container['PublicApi\Model\BulkTransactions'] = function (Slim\Container $c) {
    return new PublicApi\Model\BulkTransactions($c->get('Bindeo\Util\ApiConnection'));
};

// Controllers
$container['PublicApi\Controller\OAuth'] = function (Slim\Container $c) {
    return new PublicApi\Controller\OAuth($c->get('PublicApi\Model\OAuth\Server'));
};

$container['PublicApi\Controller\General'] = function (Slim\Container $c) {
    return new PublicApi\Controller\General($c->get('Bindeo\Util\ApiConnection'));
};

$container['PublicApi\Controller\Accounts'] = function (Slim\Container $c) {
    return new PublicApi\Controller\Accounts($c->get('Bindeo\Util\ApiConnection'));
};

$container['PublicApi\Controller\StoreData'] = function (Slim\Container $c) {
    return new PublicApi\Controller\StoreData($c->get('Bindeo\Util\ApiConnection'));
};

$container['PublicApi\Controller\BulkTransactions'] = function (Slim\Container $c) {
    return new PublicApi\Controller\BulkTransactions($c->get('PublicApi\Model\BulkTransactions'));
};