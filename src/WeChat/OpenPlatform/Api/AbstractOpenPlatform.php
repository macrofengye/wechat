<?php

namespace WeChat\WeChat\OpenPlatform\Api;

use Slim\Http\Response;
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
     * Response
     *
     * @var \Slim\Http\Response
     */
    protected $response;

    /**
     * AbstractOpenPlatform constructor.
     *
     * @param \WeChat\WeChat\OpenPlatform\AccessToken $accessToken
     * @param \Slim\Http\Request $request
     * @param \Slim\Http\Response $response
     */
    public function __construct(AccessToken $accessToken, Request $request, Response $response)
    {
        parent::__construct($accessToken);

        $this->request = $request;
        $this->response = $response;
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
