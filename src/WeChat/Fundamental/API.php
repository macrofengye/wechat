<?php

namespace WeChat\WeChat\Fundamental;

use WeChat\WeChat\Core\AbstractAPI;

class API extends AbstractAPI
{
    const API_CLEAR_QUOTA = 'https://api.weixin.qq.com/cgi-bin/clear_quota';
    const API_CALLBACK_IP = 'https://api.weixin.qq.com/cgi-bin/getcallbackip';

    /**
     * Clear quota.
     *
     * @return \WeChat\WeChat\Support\Collection
     */
    public function clearQuota()
    {
        $appid = $this->getAccessToken()->getAppId();

        return $this->parseJSON('json', [self::API_CLEAR_QUOTA, compact('appid')]);
    }

    /**
     * Get wechat callback ip.
     *
     * @return \WeChat\WeChat\Support\Collection
     */
    public function getCallbackIp()
    {
        return $this->parseJSON('get', [self::API_CALLBACK_IP]);
    }
}
