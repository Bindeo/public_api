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
            case 'user_identities':
                // Fill the entity class
                if (!$this->entity) {
                    $this->entity = 'PublicApi\Entity\UserIdentity';
                } elseif ($this->entity != 'PublicApi\Entity\UserIdentity') {
                    throw new \Exception(500);
                }

                $object = new UserIdentity((array)$data->attributes);
                $res = [$object->getIdIdentity(), $object];
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
            case 'oauth_tokens':
                // Fill the entity class
                if (!$this->entity) {
                    $this->entity = 'PublicApi\Entity\OAuthToken';
                } elseif ($this->entity != 'PublicApi\Entity\OAuthToken') {
                    throw new \Exception(500);
                }

                $object = new OAuthToken((array)$data->attributes);
                $res = [$object->getToken(), $object];
                break;
            case 'bulk_transactions':
                // Fill the entity class
                if (!$this->entity) {
                    $this->entity = 'PublicApi\Entity\BulkTransaction';
                } elseif ($this->entity != 'PublicApi\Entity\BulkTransaction') {
                    throw new \Exception(500);
                }

                $object = new BulkTransaction((array)$data->attributes);
                $res = [$object->getIdBulkTransaction(), $object];
                break;
            case 'bulk_types':
                // Fill the entity class
                if (!$this->entity) {
                    $this->entity = 'PublicApi\Entity\BulkType';
                } elseif ($this->entity != 'PublicApi\Entity\BulkType') {
                    throw new \Exception(500);
                }

                $object = new BulkType((array)$data->attributes);
                $res = [$object->getType(), $object];
                break;
            case 'files':
                // Fill the entity class
                if (!$this->entity) {
                    $this->entity = 'PublicApi\Entity\File';
                } elseif ($this->entity != 'PublicApi\Entity\File') {
                    throw new \Exception(500);
                }

                $object = new File((array)$data->attributes);
                $res = [$object->getIdFile(), $object];
                break;
            default:
                throw new \Exception(500);
        }

        return $res;
    }

}