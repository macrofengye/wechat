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
    protected $prefix = 'polymer.common.mini.program.access_token.';
}
