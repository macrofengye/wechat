<?php

namespace WeChat\Providers;

use WeChat\WeChat\Payment\LuckyMoney\LuckyMoney;
use WeChat\WeChat\Payment\Merchant;
use WeChat\WeChat\Payment\MerchantPay\MerchantPay;
use WeChat\WeChat\Payment\Payment;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class PaymentProvider.
 */
class PaymentProvider implements ServiceProviderInterface
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
        $pimple['merchant'] = function ($pimple) {
            $config = array_merge(
                ['app_id' => app()->config('wechat.app_id')],
                app()->config('wechat.payment', [])
            );

            return new Merchant($config);
        };

        $pimple['payment'] = function ($pimple) {
            return new Payment($pimple['merchant']);
        };

        $pimple['lucky_money'] = function ($pimple) {
            return new LuckyMoney($pimple['merchant']);
        };

        $pimple['merchant_pay'] = function ($pimple) {
            return new MerchantPay($pimple['merchant']);
        };
    }
}
