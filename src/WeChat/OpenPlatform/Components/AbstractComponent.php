<?php

namespace WeChat\WeChat\OpenPlatform\Components;

use WeChat\WeChat\Core\AbstractAPI;
use WeChat\WeChat\Core\AccessToken;
use WeChat\WeChat\Core\Exception;
use Slim\Http\Request;

abstract class AbstractComponent extends AbstractAPI
{
    /**
     * Config.
     *
     * @var array
     */
    protected $config;

    /**
     * AppId, component app id.
     *
     * @var string
     */
    private $appId;

    /**
     * Request.
     *
     * @var Request
     */
    protected $request;

    /**
     * AbstractComponent constructor.
     *
     * @param AccessToken $accessToken
     * @param array $config
     * @param \Slim\Http\Request $request
     */
    public function __construct($accessToken, array $config, Request $request)
    {
        parent::__construct($accessToken);
        $this->config = $config;
        $this->request = $request;
    }

    /**
     * Get AppId.
     *
     * @return string
     * @throws Exception When app id is not present.
     */
    public function getAppId()
    {
        if ($this->appId) {
            return $this->appId;
        }
        if (isset($this->config['open_platform'])) {
            $this->appId = $this->config['open_platform']['app_id'];
        } else {
            $this->appId = $this->config['app_id'];
        }
        if (empty($this->appId)) {
            throw new Exception('App Id is not present.');
        }
        return $this->appId;
    }

    /**
     * Set AppId.
     *
     * @param string $appId
     *
     * @return $this
     */
    public function setAppId($appId)
    {
        $this->appId = $appId;
        return $this;
    }
}
