<?php
/**
 * User: macro chen <macro_fengye@163.com>
 * Date: 2017/6/11
 * Time: 21:05
 */

namespace WeChat\Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use WeChat\WeChat\Comment\Comment;

class CommentProvider implements ServiceProviderInterface
{
    public function register(Container $pimple)
    {
        $pimple['comment'] = function ($pimple) {
            return new Comment($pimple['access_token']);
        };
    }
}
