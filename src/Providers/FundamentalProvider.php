<?php
/**
 * User: macro chen <macro_fengye@163.com>
 * Date: 2017/6/11
 * Time: 21:00
 */

namespace WeChat\Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use WeChat\WeChat\Fundamental\API;

class FundamentalProvider implements ServiceProviderInterface
{
    public function register(Container $pimple)
    {
        $pimple['fundamental'] = function ($pimple) {
            return new API($pimple['access_token']);
        };
    }
}