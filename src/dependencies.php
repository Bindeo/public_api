<?php
// DIC configuration

$container = $app->getContainer();

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], Monolog\Logger::DEBUG));

    return $logger;
};

/*
// OAuth2
$container['Api\Model\General\OAuthStorage'] = function ($c) {
    return new \Api\Model\General\OAuthStorage($c->get('settings')['oauth']);
};

$container['Api\Model\General\OAuth'] = function ($c) {
    return new \Api\Model\General\OAuth($c->get('Api\Model\General\OAuthStorage'));
};

$container['Api\Middleware\OAuth'] = function ($c) {
    return new Api\Middleware\OAuth($c->get('Api\Model\General\OAuth'));
};
*/

// Private API connection
$container['Bindeo\Util\ApiConnection'] = function ($c) {
    return new \Bindeo\Util\ApiConnection($c->get('settings')['api']['url'], $c->get('settings')['api']['token'], 'api');
};

// Controllers
$container['PublicApi\Controller\General'] = function ($c) {
    return new PublicApi\Controller\General($c->get('Bindeo\Util\ApiConnection'));
};