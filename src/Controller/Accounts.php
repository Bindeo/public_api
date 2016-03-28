<?php

namespace PublicApi\Controller;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class Accounts
{
    private $model;

    public function __construct(\PublicApi\Model\Accounts $model)
    {
        $this->model = $model;
    }
}