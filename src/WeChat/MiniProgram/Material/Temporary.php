<?php

namespace WeChat\WeChat\MiniProgram\Material;

use WeChat\WeChat\Material\Temporary as BaseTemporary;

/**
 * Class Temporary.
 */
class Temporary extends BaseTemporary
{
    public function __construct()
    {
        $accessToken = func_get_args()[0];

        parent::__construct($accessToken);
    }
}
