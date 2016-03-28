<?php

namespace PublicApi\Model;

use Bindeo\Util\ApiConnection;
use \Psr\Log\LoggerInterface;
use Slim\Http\Response;

/**
 * Class Accounts to manage Accounts controller functionality
 * @package PublicApi\Model
 */
class Accounts
{
    private $api;

    public function __construct(ApiConnection $api)
    {
        $this->api = $api;
    }
}