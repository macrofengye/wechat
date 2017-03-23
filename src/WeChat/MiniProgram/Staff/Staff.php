<?php

namespace WeChat\WeChat\MiniProgram\Staff;

use WeChat\WeChat\Staff\Staff as BaseStaff;

class Staff extends BaseStaff
{
    public function __construct()
    {
        $accessToken = func_get_args()[0];

        parent::__construct($accessToken);
    }
}
