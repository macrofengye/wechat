<?php
namespace WeChat\WeChat\OpenPlatform\EventHandlers;

use WeChat\WeChat\Support\Collection;

interface EventHandler
{
    /**
     * Handle event.
     *
     * @param Collection $message
     *
     * @return mixed
     */
    public function handle(Collection $message);

}
