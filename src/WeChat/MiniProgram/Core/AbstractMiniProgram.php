<?php

namespace WeChat\WeChat\MiniProgram\Core;

use WeChat\WeChat\Core\AbstractAPI;

class AbstractMiniProgram extends AbstractAPI
{
    /**
     * Mini program config.
     *
     * @var array
     */
    protected $config;

    /**
     * AbstractMiniProgram constructor.
     *
     * @param \WeChat\WeChat\MiniProgram\AccessToken $accessToken
     * @param array                               $config
     */
    public function __construct($accessToken, $config)
    {
        parent::__construct($accessToken);

        $this->config = $config;
    }
}
