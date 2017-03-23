<?php
namespace WeChat\WeChat\OpenPlatform\EventHandlers;

use WeChat\WeChat\OpenPlatform\Traits\VerifyTicketTrait;
use WeChat\WeChat\OpenPlatform\VerifyTicket;
use WeChat\WeChat\Support\Collection;

class ComponentVerifyTicket implements EventHandler
{
    use VerifyTicketTrait;

    public function __construct(VerifyTicket $verifyTicket)
    {
        $this->setVerifyTicket($verifyTicket);
    }

    /**
     * {@inheritdoc}.
     */
    public function handle(Collection $message)
    {
        $this->getVerifyTicket()->cache($message);
        return $message;
    }
}
