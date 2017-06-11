<?php
/**
 * User: macro chen <chen_macro@163.com>
 * Date: 16-10-10
 * Time: 下午1:38
 */

namespace WeChat\Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Doctrine\Common\Cache\FilesystemCache;
use WeChat\WeChat\Core\AccessToken;

class AccessTokenProvider implements ServiceProviderInterface
{
    /**
     * Registers services on the given container.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param Container $pimple A container instance
     */
    public function register(Container $pimple)
    {
        $pimple['access_token'] = function (Container $container) {
            try {
                if (!$container['application']->component('wechatCache')) {
                    $weChatConfig = weChatConfig();
                    $cache = new FilesystemCache(sys_get_temp_dir() . '/polymer/' . $weChatConfig['name']);
                } else {
                    $cache = $container['application']->component('wechatCache');
                }
                $container['cache'] = $cache;
                return new AccessToken($weChatConfig['app_id'], $weChatConfig['secret'], $cache);
            } catch (\Exception $e) {
                return $container['response']
                    ->withStatus(500)
                    ->withHeader('Content-Type', 'text/html')->write($e->getMessage());
            }
        };
    }
}