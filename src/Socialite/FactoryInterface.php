<?php
namespace WeChat\Socialite;

/**
 * Interface FactoryInterface.
 */
interface FactoryInterface
{
    /**
     * Get an OAuth provider implementation.
     *
     * @param string $driver
     *
     * @return \WeChat\Socialite\
     */
    public function driver($driver = null);
}
