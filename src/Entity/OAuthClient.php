<?php

namespace PublicApi\Entity;

use Bindeo\DataModel\OAuthClientAbstract;
use League\OAuth2\Server\Entities\Interfaces\ClientEntityInterface;
use League\OAuth2\Server\Entities\Traits\EntityTrait;

class OAuthClient extends OAuthClientAbstract implements ClientEntityInterface
{
    use EntityTrait;

    protected $redirectUri;

    /**
     * Set the client's redirect uri.
     *
     * @param string $redirectUri
     *
     * @return $this
     */
    public function setRedirectUri($redirectUri)
    {
        $this->redirectUri = $redirectUri;

        return $this;
    }

    /**
     * Returns the registered redirect URI.
     * @return string
     */
    public function getRedirectUri()
    {
        return $this->redirectUri;
    }
}