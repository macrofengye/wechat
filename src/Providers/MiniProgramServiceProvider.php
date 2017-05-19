<?php

namespace WeChat\Providers;

/**
 * Class MiniProgramServiceProvider.
 */
use WeChat\WeChat\Encryption\Encryptor;
use WeChat\WeChat\MiniProgram\AccessToken;
use WeChat\WeChat\MiniProgram\Material\Temporary;
use WeChat\WeChat\MiniProgram\MiniProgram;
use WeChat\WeChat\MiniProgram\Notice\Notice;
use WeChat\WeChat\MiniProgram\QRCode\QRCode;
use WeChat\WeChat\MiniProgram\Server\Guard;
use WeChat\WeChat\MiniProgram\Sns\Sns;
use WeChat\WeChat\MiniProgram\Staff\Staff;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class MiniProgramServiceProvider.
 */
class MiniProgramServiceProvider implements ServiceProviderInterface
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
                app()->config('mini_program.app_id'),
                app()->config('mini_program.secret'),
                $pimple['cache']
            );
        };

        $pimple['mini_program.encryptor'] = function ($pimple) {
            try {
                return new Encryptor(
                    app()->config('mini_program.app_id'),
                    app()->config('mini_program.token'),
                    app()->config('mini_program.aes_key')
                );
            }catch (\Exception $e){
                return null;
            }
        };

        $pimple['mini_program.server'] = function ($pimple) {
            $server = new Guard(app()->config('mini_program.token'));
            $server->debug(app()->config('mini_program.debug'));
            $server->setEncryptor(app()->config('mini_program.encryptor'));

            return $server;
        };

        $pimple['mini_program.staff'] = function ($pimple) {
            return new Staff(app()->config('mini_program.access_token'));
        };

        $pimple['mini_program.notice'] = function ($pimple) {
            return new Notice(app()->config('mini_program.access_token'));
        };

        $pimple['mini_program.material_temporary'] = function ($pimple) {
            return new Temporary(app()->config('mini_program.access_token'));
        };

        $pimple['mini_program.sns'] = function ($pimple) {
            return new Sns(
                app()->config('mini_program.access_token'),
                app()->config('mini_program')
            );
        };

        $pimple['mini_program.qrcode'] = function ($pimple) {
            return new QRCode(
                app()->config('mini_program.access_token'),
                app()->config('mini_program')
            );
        };

        $pimple['mini_program'] = function ($pimple) {
            return new MiniProgram($pimple);
        };
    }
}
