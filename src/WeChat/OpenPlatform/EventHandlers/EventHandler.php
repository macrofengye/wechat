<?php
namespace WeChat\WeChat\OpenPlatform\EventHandlers;

abstract class EventHandler
{
    /**
     * Handle an incoming event message from WeChat server-side.
     *
     * @param \WeChat\WeChat\Support\Collection $message
     */
    abstract public function handle($message);
}
