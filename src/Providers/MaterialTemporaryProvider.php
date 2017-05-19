<?php
/**
 * User: macro chen <macro_fengye@163.com>
 * Date: 17-5-19
 * Time: 上午9:55
 */

namespace WeChat\Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use WeChat\WeChat\Material\Temporary;

class MaterialTemporaryProvider implements ServiceProviderInterface
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
        $pimple['material_temporary'] = function (Container $container) {
            return new Temporary($container['access_token']);
        };
    }
}
