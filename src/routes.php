<?php
// Routes
// OAuth
$app->post('/access_token', 'PublicApi\Controller\OAuth:token');

// General data routes
$app->group('/general', function () {
    $this->get('/account-types', 'PublicApi\Controller\General:accountTypes');
    $this->get('/file-types', 'PublicApi\Controller\General:fileTypes');
    $this->get('/media-types', 'PublicApi\Controller\General:mediaTypes');
});