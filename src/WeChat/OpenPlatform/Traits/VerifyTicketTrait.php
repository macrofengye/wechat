<?php
namespace WeChat\WeChat\OpenPlatform\Traits;

use WeChat\WeChat\OpenPlatform\VerifyTicket;

trait VerifyTicketTrait
{
    /**
     * Verify Ticket.
     *
     * @var \WeChat\WeChat\OpenPlatform\VerifyTicket
     */
    protected $verifyTicket;

    /**
     * Set verify ticket instance.
     *
     * @param \WeChat\WeChat\OpenPlatform\VerifyTicket $verifyTicket
     *
     * @return $this
     */
    public function setVerifyTicket(VerifyTicket $verifyTicket)
    {
        $this->verifyTicket = $verifyTicket;
        return $this;
    }

    /**
     * Get verify ticket instance.
     *
     * @return \WeChat\WeChat\OpenPlatform\VerifyTicket
     */
    public function getVerifyTicket()
    {
        return $this->verifyTicket;
    }
}
