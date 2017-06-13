<?php
/**
 * User: macro chen <macro_fengye@163.com>
 * Date: 17-6-12
 * Time: 上午8:03
 */

namespace WeChat\Providers;

use Doctrine\Common\Cache\FilesystemCache;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class AccessTokenCacheProvider implements ServiceProviderInterface
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
        $pimple['AccessTokenCache'] = function (Container $container) {
            try {
                $cache = new FilesystemCache(sys_get_temp_dir() . '/polymer/' . $container->offsetGet('AccessTokenCacheName'));
            } catch (\Exception $e) {
                $cache = null;
            }
            return $cache;
        };
    }
}
