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

// Accounts routes
$app->group('/account', function () {
    $this->post('', 'PublicApi\Controller\Accounts:create');
    $this->put('', 'PublicApi\Controller\Accounts:modify');
    $this->delete('', 'PublicApi\Controller\Accounts:delete');
    $this->get('/password', 'PublicApi\Controller\Accounts:resetPassword');
    $this->put('/password', 'PublicApi\Controller\Accounts:modifyPassword');
    $this->put('/type', 'PublicApi\Controller\Accounts:changeType');
    $this->get('/token', 'PublicApi\Controller\Accounts:resendToken');
    $this->get('/identities', 'PublicApi\Controller\Accounts:getIdentities');
    $this->put('/identities', 'PublicApi\Controller\Accounts:saveIdentity');
});

// Direct access to blockchain
$app->group('/advanced/blockchain', function () {
    $this->post('', 'PublicApi\Controller\StoreData:postBlockchainData');
    $this->get('', 'PublicApi\Controller\StoreData:getBlockchainData');
});

// Bulk transaction routes
$app->group('/bulk', function () {
    $this->post('', 'PublicApi\Controller\BulkTransactions:createBulk');
    $this->get('/verify', 'PublicApi\Controller\BulkTransactions:verifyFile');
    $this->get('/types', 'PublicApi\Controller\BulkTransactions:bulkTypes');
    $this->put('/{id}', 'PublicApi\Controller\BulkTransactions:closeBulk');
    $this->delete('/{id}', 'PublicApi\Controller\BulkTransactions:deleteBulk');
    $this->post('/{id}', 'PublicApi\Controller\BulkTransactions:addItem');
    $this->get('/{id}', 'PublicApi\Controller\BulkTransactions:getBulk');
});