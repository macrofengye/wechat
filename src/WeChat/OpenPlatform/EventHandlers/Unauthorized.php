<?php
namespace WeChat\WeChat\OpenPlatform\EventHandlers;

use WeChat\WeChat\Support\Collection;

class Unauthorized extends Authorized
{
    /**
     * @inheritdoc
     */
    public function handle(Collection $message)
    {
        $this->authorization->setFromAuthMessage($message);
        $this->authorization->removeAuthorizerAccessToken();
        $this->authorization->removeAuthorizerRefreshToken();
        return $message;
    }
}
