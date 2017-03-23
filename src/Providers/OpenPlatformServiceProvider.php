<?php

namespace WeChat\Providers;

use WeChat\WeChat\OpenPlatform\Authorization;
use WeChat\WeChat\OpenPlatform\AuthorizerToken;
use WeChat\WeChat\OpenPlatform\Components\Authorizer;
use WeChat\WeChat\OpenPlatform\EventHandlers\Authorized;
use WeChat\WeChat\OpenPlatform\EventHandlers\ComponentVerifyTicket;
use WeChat\WeChat\OpenPlatform\EventHandlers\Unauthorized;
use WeChat\WeChat\OpenPlatform\EventHandlers\UpdateAuthorized;
use WeChat\WeChat\Encryption\Encryptor;
use WeChat\WeChat\OpenPlatform\AccessToken;
use WeChat\WeChat\OpenPlatform\Guard;
use WeChat\WeChat\OpenPlatform\OpenPlatform;
use WeChat\WeChat\OpenPlatform\VerifyTicket;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class OpenPlatformServiceProvider implements ServiceProviderInterface
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
        $pimple['open_platform.verify_ticket'] = function ($pimple) {
            return new VerifyTicket(
                app()->config('open_platform.app_id'),
                $pimple['cache']
            );
        };

        $pimple['open_platform.access_token'] = function ($pimple) {
            return new AccessToken(
                app()->config('open_platform.app_id'),
                app()->config('open_platform.secret'),
                $pimple['open_platform.verify_ticket'],
                $pimple['cache']
            );
        };

        $pimple['open_platform.encryptor'] = function ($pimple) {
            try {
                return new Encryptor(
                    app()->config('open_platform.app_id'),
                    app()->config('open_platform.token'),
                    app()->config('open_platform.aes_key')
                );
            } catch (\Exception $e) {
                return null;
            }
        };

        $pimple['open_platform'] = function ($pimple) {
            $server = new Guard(app()->config('open_platform.token'));
            $server->debug(app()->config('open_platform.debug'));
            $server->setEncryptor($pimple['open_platform.encryptor']);
            $server->setContainer($pimple);
            $platform = new OpenPlatform(
                $server,
                $pimple['open_platform.access_token'],
                app()->config('open_platform')
            );
            $platform->setContainer($pimple);
            return $platform;
        };

        $pimple['open_platform.authorizer'] = function ($pimple) {
            return new Authorizer(
                $pimple['open_platform.access_token'],
                app()->config('open_platform')
            );
        };

        $pimple['open_platform.authorization'] = function ($pimple) {
            return new Authorization(
                $pimple['open_platform.authorizer'],
                app()->config('open_platform.app_id'),
                $pimple['cache']
            );
        };

        $pimple['open_platform.authorizer_token'] = function ($pimple) {
            return new AuthorizerToken(
                app()->config('open_platform.app_id'),
                $pimple['open_platform.authorization']
            );
        };

        // Authorization events handlers.
        $pimple['open_platform.handlers.component_verify_ticket'] = function ($pimple) {
            return new ComponentVerifyTicket($pimple['open_platform.verify_ticket']);
        };
        $pimple['open_platform.handlers.authorized'] = function ($pimple) {
            return new Authorized($pimple['open_platform.authorization']);
        };
        $pimple['open_platform.handlers.updateauthorized'] = function ($pimple) {
            return new UpdateAuthorized($pimple['open_platform.authorization']);
        };
        $pimple['open_platform.handlers.unauthorized'] = function ($pimple) {
            return new Unauthorized($pimple['open_platform.authorization']);
        };
    }
}

