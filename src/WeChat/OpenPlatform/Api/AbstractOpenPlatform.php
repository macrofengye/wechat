<?php

namespace WeChat\WeChat\OpenPlatform\Api;

use WeChat\WeChat\Core\AbstractAPI;
use WeChat\WeChat\OpenPlatform\AccessToken;
use Slim\Http\Request;

abstract class AbstractOpenPlatform extends AbstractAPI
{
    /**
     * Request.
     *
     * @var \Slim\Http\Request
     */
    protected $request;

    /**
     * AbstractOpenPlatform constructor.
     *
     * @param \WeChat\WeChat\OpenPlatform\AccessToken      $accessToken
     * @param \Slim\Http\Request $request
     */
    public function __construct(AccessToken $accessToken, Request $request)
    {
        parent::__construct($accessToken);

        $this->request = $request;
    }

    /**
     * Get OpenPlatform AppId.
     *
     * @return string
     */
    public function getAppId()
    {
        return $this->getAccessToken()->getAppId();
    }
}
