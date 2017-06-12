<?php

namespace WeChat\Providers;

use WeChat\WeChat\MiniProgram\AccessToken;
use WeChat\WeChat\MiniProgram\Encryption\Encryptor;
use WeChat\WeChat\MiniProgram\Material\Temporary;
use WeChat\WeChat\MiniProgram\MiniProgram;
use WeChat\WeChat\MiniProgram\Notice\Notice;
use WeChat\WeChat\MiniProgram\QRCode\QRCode;
use WeChat\WeChat\MiniProgram\Server\Guard;
use WeChat\WeChat\MiniProgram\Sns\Sns;
use WeChat\WeChat\MiniProgram\Staff\Staff;
use WeChat\WeChat\MiniProgram\Stats\Stats;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class MiniProgramProvider.
 */
class MiniProgramProvider implements ServiceProviderInterface
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
        $pimple['mini_program.access_token'] = function ($pimple) {
            return new AccessToken(
                $pimple['application']->config('mini_program.app_id'),
                $pimple['application']->config('mini_program.secret'),
                $pimple['cache']
            );
        };

        $pimple['mini_program.encryptor'] = function ($pimple) {
            return new Encryptor(
                $pimple['application']->config('mini_program.app_id'),
                $pimple['application']->config('mini_program.token'),
                $pimple['application']->config('mini_program.aes_key')
            );
        };

        $pimple['mini_program.server'] = function ($pimple) {
            $server = new Guard($pimple['application']->config('mini_program.token'));
            $server->debug($pimple['application']->config('mini_program.debug'));
            $server->setEncryptor($pimple['mini_program.encryptor']);
            return $server;
        };

        $pimple['mini_program.staff'] = function ($pimple) {
            return new Staff($pimple['mini_program.access_token']);
        };

        $pimple['mini_program.notice'] = function ($pimple) {
            return new Notice($pimple['mini_program.access_token']);
        };

        $pimple['mini_program.material_temporary'] = function ($pimple) {
            return new Temporary($pimple['mini_program.access_token']);
        };

        $pimple['mini_program.stats'] = function ($pimple) {
            return new Stats(
                $pimple['mini_program.access_token'],
                $pimple['application']->config('mini_program')
            );
        };

        $pimple['mini_program.sns'] = function ($pimple) {
            return new Sns(
                $pimple['mini_program.access_token'],
                $pimple['application']->config('mini_program')
            );
        };

        $pimple['mini_program.qrcode'] = function ($pimple) {
            return new QRCode(
                $pimple['mini_program.access_token'],
                $pimple['application']->config('mini_program')
            );
        };

        $pimple['mini_program'] = function ($pimple) {
            return new MiniProgram($pimple);
        };
    }
}
