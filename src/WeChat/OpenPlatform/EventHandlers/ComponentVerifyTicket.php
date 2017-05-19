<?php

namespace WeChat\WeChat\OpenPlatform\EventHandlers;

use WeChat\WeChat\OpenPlatform\VerifyTicket;

class ComponentVerifyTicket extends EventHandler
{
    /**
     * VerifyTicket.
     *
     * @var \WeChat\WeChat\OpenPlatform\VerifyTicket
     */
    protected $verifyTicket;

    /**
     * Constructor.
     *
     * @param \WeChat\WeChat\OpenPlatform\VerifyTicket $verifyTicket
     */
    public function __construct(VerifyTicket $verifyTicket)
    {
        $this->verifyTicket = $verifyTicket;
    }

    /**
     * {@inheritdoc}.
     */
    public function handle($message)
    {
        $this->verifyTicket->setTicket($message->get('ComponentVerifyTicket'));
    }
}
