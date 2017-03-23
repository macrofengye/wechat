<?php
namespace WeChat\WeChat\OpenPlatform\EventHandlers;

use WeChat\WeChat\OpenPlatform\Authorization;
use WeChat\WeChat\Support\Collection;

class Authorized implements EventHandler
{
    /**
     * @var Authorization
     */
    protected $authorization;

    public function __construct(Authorization $authorization)
    {
        $this->authorization = $authorization;
    }

    /**
     * {@inheritdoc}.
     * @throws \Exception
     */
    public function handle(Collection $message)
    {
        $this->authorization->setFromAuthMessage($message);
        return $this->authorization->handleAuthorization();
    }
}
