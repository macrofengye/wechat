<?php

namespace WeChat\Providers;

use WeChat\WeChat\Card\Card;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class CardProvider.
 */
class CardProvider implements ServiceProviderInterface
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
        $pimple['card'] = function ($pimple) {
            $card = new Card($pimple['access_token']);
            $card->setCache($pimple['cache']);

            return $card;
        };
    }
}
