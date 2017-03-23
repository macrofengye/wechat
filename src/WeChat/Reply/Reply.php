<?php
namespace WeChat\WeChat\Reply;

use WeChat\WeChat\Core\AbstractAPI;

/**
 * Class Reply.
 */
class Reply extends AbstractAPI
{
    const API_GET_CURRENT_SETTING = 'https://api.weixin.qq.com/cgi-bin/get_current_autoreply_info';

    /**
     * Get current auto reply settings.
     *
     * @return \WeChat\WeChat\Support\Collection
     */
    public function current()
    {
        return $this->parseJSON('get', [self::API_GET_CURRENT_SETTING]);
    }
}
