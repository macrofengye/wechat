<?php

namespace WeChat\WeChat\MiniProgram;

use WeChat\WeChat\Core\AccessToken as CoreAccessToken;

/**
 * Class AccessToken.
 */
class AccessToken extends CoreAccessToken
{
    /**
     * {@inheritdoc}.
     */
    protected $prefix = 'macro.wechat.common.mini.program.access_token.';
}
