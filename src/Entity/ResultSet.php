<?php

namespace PublicApi\Entity;

use Bindeo\DataModel\ClientResultSetAbstract;

class ResultSet extends ClientResultSetAbstract
{
    /**
     * Get the appropriate object
     *
     * @param \stdClass $data
     *
     * @return array
     * @throws \Exception
     */
    protected function getObject(\stdClass $data)
    {
        switch ($data->type) {
            case 'account_type':
                // Fill the entity class
                if (!$this->entity) {
                    $this->entity = 'PublicApi\Entity\AccountType';
                } elseif ($this->entity != 'PublicApi\Entity\AccountType') {
                    throw new \Exception(500);
                }

                $object = new AccountType((array)$data->attributes);
                $res = [$object->getIdType(), $object];
                break;
            case 'file_type':
                // Fill the entity class
                if (!$this->entity) {
                    $this->entity = 'PublicApi\Entity\FileType';
                } elseif ($this->entity != 'PublicApi\Entity\FileType') {
                    throw new \Exception(500);
                }

                $object = new FileType((array)$data->attributes);
                $res = [$object->getIdType(), $object];
                break;
            case 'media_type':
                // Fill the entity class
                if (!$this->entity) {
                    $this->entity = 'PublicApi\Entity\MediaType';
                } elseif ($this->entity != 'PublicApi\Entity\MediaType') {
                    throw new \Exception(500);
                }

                $object = new MediaType((array)$data->attributes);
                $res = [$object->getIdType(), $object];
                break;
            case 'users':
                // Fill the entity class
                if (!$this->entity) {
                    $this->entity = 'PublicApi\Entity\User';
                } elseif ($this->entity != 'PublicApi\Entity\User') {
                    throw new \Exception(500);
                }

                $object = new User((array)$data->attributes);
                $res = [$object->getIdUser(), $object];
                break;
            case 'oauth_clients':
                // Fill the entity class
                if (!$this->entity) {
                    $this->entity = 'PublicApi\Entity\OAuthClient';
                } elseif ($this->entity != 'PublicApi\Entity\OAuthClient') {
                    throw new \Exception(500);
                }

                $object = new OAuthClient((array)$data->attributes);
                $res = [$object->getIdClient(), $object];
                break;
            default:
                throw new \Exception(500);
        }

        return $res;
    }

}