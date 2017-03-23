<?php

namespace WeChat\Providers;

use WeChat\WeChat\Encryption\Encryptor;
use WeChat\WeChat\MiniProgram\AccessToken;
use WeChat\WeChat\MiniProgram\MiniProgram;
use WeChat\WeChat\MiniProgram\Server\Guard;
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
        $pimple['mini_program_access_token'] = function ($pimple) {
            $config = app()->config('mini_program');

            return new AccessToken(
                $config['app_id'],
                $config['secret'],
                $pimple['cache']
            );
        };

        $pimple['mini_program_encryptor'] = function ($pimple) {
            $config = app()->config('mini_program');

            return new Encryptor(
                $config['app_id'],
                $config['token'],
                $config['aes_key']
            );
        };

        $pimple['mini_program'] = function ($pimple) {
            $config = app()->config('mini_program');

            $server = new Guard($config['token']);

            $server->debug(app()->config('mini_program.debug'));

            $server->setEncryptor($pimple['mini_program_encryptor']);

            return new MiniProgram(
                $server,
                $pimple['mini_program_access_token'],
                $config
            );
        };
    }
}
