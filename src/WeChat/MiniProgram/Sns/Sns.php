<?php

namespace WeChat\WeChat\MiniProgram\Sns;

use WeChat\WeChat\MiniProgram\Core\AbstractMiniProgram;

class Sns extends AbstractMiniProgram
{
    /**
     * Api.
     */
    const JSCODE_TO_SESSION = 'https://api.weixin.qq.com/sns/jscode2session';

    /**
     * JsCode 2 session key.
     *
     * @param string $jsCode
     *
     * @return \WeChat\WeChat\Support\Collection
     */
    public function getSessionKey($jsCode)
    {
        $params = [
            'appid' => $this->config['app_id'],
            'secret' => $this->config['secret'],
            'js_code' => $jsCode,
            'grant_type' => 'authorization_code',
        ];

        return $this->parseJSON('GET', [self::JSCODE_TO_SESSION, $params]);
    }
}
