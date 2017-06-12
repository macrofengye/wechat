<?php

namespace WeChat\Providers;

use WeChat\WeChat\Encryption\Encryptor;
use WeChat\WeChat\OpenPlatform\AccessToken;
use WeChat\WeChat\OpenPlatform\Api\BaseApi;
use WeChat\WeChat\OpenPlatform\Api\PreAuthorization;
use WeChat\WeChat\OpenPlatform\Authorizer;
use WeChat\WeChat\OpenPlatform\AuthorizerAccessToken;
use WeChat\WeChat\OpenPlatform\EventHandlers;
use WeChat\WeChat\OpenPlatform\Guard;
use WeChat\WeChat\OpenPlatform\OpenPlatform;
use WeChat\WeChat\OpenPlatform\VerifyTicket;
use WeChat\Socialite\SocialiteManager as Socialite;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class OpenPlatformProvider implements ServiceProviderInterface
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
                $pimple['application']->config('open_platform.app_id'),
                $pimple['cache']
            );
        };

        $pimple['open_platform.access_token'] = function ($pimple) {
            $accessToken = new AccessToken(
                $pimple['application']->config('open_platform.app_id'),
                $pimple['application']->config('open_platform.secret'),
                $pimple['cache']
            );
            $accessToken->setVerifyTicket($pimple['open_platform.verify_ticket']);

            return $accessToken;
        };

        $pimple['open_platform.encryptor'] = function ($pimple) {
            return new Encryptor(
                $pimple['application']->config('open_platform.app_id'),
                $pimple['application']->config('open_platform.token'),
                $pimple['application']->config('open_platform.aes_key')
            );
        };

        $pimple['open_platform'] = function ($pimple) {
            return new OpenPlatform($pimple);
        };

        $pimple['open_platform.server'] = function ($pimple) {
            $server = new Guard($pimple['application']->config('open_platform.token'));
            $server->debug($pimple['application']->config('open_platform.debug'));
            $server->setEncryptor($pimple['open_platform.encryptor']);
            $server->setHandlers([
                Guard::EVENT_AUTHORIZED => $pimple['open_platform.handlers.authorized'],
                Guard::EVENT_UNAUTHORIZED => $pimple['open_platform.handlers.unauthorized'],
                Guard::EVENT_UPDATE_AUTHORIZED => $pimple['open_platform.handlers.updateauthorized'],
                Guard::EVENT_COMPONENT_VERIFY_TICKET => $pimple['open_platform.handlers.component_verify_ticket'],
            ]);
            return $server;
        };

        $pimple['open_platform.pre_auth'] = $pimple['open_platform.pre_authorization'] = function ($pimple) {
            return new PreAuthorization(
                $pimple['open_platform.access_token'],
                $pimple['request']
            );
        };

        $pimple['open_platform.api'] = function ($pimple) {
            return new BaseApi(
                $pimple['open_platform.access_token'],
                $pimple['request']
            );
        };

        $pimple['open_platform.authorizer'] = function ($pimple) {
            return new Authorizer(
                $pimple['open_platform.api'],
                $pimple['application']->config('open_platform.app_id'),
                $pimple['cache']
            );
        };

        $pimple['open_platform.authorizer_access_token'] = function ($pimple) {
            return new AuthorizerAccessToken(
                $pimple['application']->config('open_platform.app_id'),
                $pimple['open_platform.authorizer']
            );
        };

        // Authorization events handlers.
        $pimple['open_platform.handlers.component_verify_ticket'] = function ($pimple) {
            return new EventHandlers\ComponentVerifyTicket($pimple['open_platform.verify_ticket']);
        };
        $pimple['open_platform.handlers.authorized'] = function () {
            return new EventHandlers\Authorized();
        };
        $pimple['open_platform.handlers.updateauthorized'] = function () {
            return new EventHandlers\UpdateAuthorized();
        };
        $pimple['open_platform.handlers.unauthorized'] = function () {
            return new EventHandlers\Unauthorized();
        };

        // OAuth for OpenPlatform.
        $pimple['open_platform.oauth'] = function ($pimple) {
            $callback = $this->prepareCallbackUrl($pimple);
            $scopes = $pimple['application']->config('open_platform.oauth', []);
            $socialite = (new Socialite([
                'wechat_open' => [
                    'client_id' => $pimple['open_platform.authorizer']->getAppId(),
                    'client_secret' => $pimple['open_platform.access_token'],
                    'redirect' => $callback,
                ],
            ]))->driver('wechat_open');

            if (!empty($scopes)) {
                $socialite->scopes($scopes);
            }

            return $socialite;
        };
    }

    /**
     * Prepare the OAuth callback url for wechat.
     *
     * @param Container $pimple
     *
     * @return string
     */
    private function prepareCallbackUrl($pimple)
    {
        $callback = $pimple['config']->get('oauth.callback');
        if (0 === stripos($callback, 'http')) {
            return $callback;
        }
        $baseUrl = $pimple['request']->getSchemeAndHttpHost();

        return $baseUrl . '/' . ltrim($callback, '/');
    }
}
