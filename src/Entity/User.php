<?php

namespace PublicApi\Entity;

use Bindeo\DataModel\UserAbstract;
use League\OAuth2\Server\Entities\Interfaces\UserEntityInterface;
use League\OAuth2\Server\Entities\Traits\EntityTrait;

class User extends UserAbstract implements UserEntityInterface
{
    use EntityTrait;
}