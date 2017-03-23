<?php

namespace WeChat\WeChat\MiniProgram;

use WeChat\WeChat\Core\Exceptions\InvalidArgumentException;
use WeChat\WeChat\MiniProgram\Material\Temporary;
use WeChat\WeChat\MiniProgram\Notice\Notice;
use WeChat\WeChat\MiniProgram\QRCode\QRCode;
use WeChat\WeChat\MiniProgram\Staff\Staff;
use WeChat\WeChat\MiniProgram\User\User;
use WeChat\WeChat\Support\Arr;

/**
 * Class MiniProgram.
 *
 * @property \WeChat\WeChat\MiniProgram\Server\Guard $server
 * @property \WeChat\WeChat\MiniProgram\User\User $user
 * @property \WeChat\WeChat\MiniProgram\Notice\Notice $notice
 * @property \WeChat\WeChat\MiniProgram\Staff\Staff $staff
 * @property \WeChat\WeChat\MiniProgram\QRCode\QRCode $qrcode
 * @property \WeChat\WeChat\MiniProgram\Material\Temporary $material_temporary
 */
class MiniProgram
{
    /**
     * Access Token.
     *
     * @var \WeChat\WeChat\MiniProgram\AccessToken
     */
    protected $accessToken;

    /**
     * Mini program config.
     *
     * @var array
     */
    protected $config;

    /**
     * Guard.
     *
     * @var \WeChat\WeChat\MiniProgram\Server\Guard
     */
    protected $server;

    /**
     * Components.
     *
     * @var array
     */
    protected $components = [
        'user' => User::class,
        'notice' => Notice::class,
        'staff' => Staff::class,
        'qrcode' => QRCode::class,
        'material_temporary' => Temporary::class,
    ];

    /**
     * MiniProgram constructor.
     *
     * @param \WeChat\WeChat\MiniProgram\Server\Guard $server
     * @param \WeChat\WeChat\MiniProgram\AccessToken $accessToken
     * @param array $config
     */
    public function __construct($server, $accessToken, $config)
    {
        $this->server = $server;

        $this->accessToken = $accessToken;

        $this->config = $config;
    }

    /**
     * Magic get access.
     *
     * @param $name
     *
     * @return mixed
     *
     * @throws InvalidArgumentException
     */
    public function __get($name)
    {
        if (property_exists($this, $name)) {
            return $this->$name;
        }

        if ($class = Arr::get($this->components, $name)) {
            return new $class($this->accessToken, $this->config);
        }

        throw new InvalidArgumentException("Property or component \"$name\" does not exists.");
    }
}
