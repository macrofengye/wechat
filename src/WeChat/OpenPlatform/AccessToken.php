<?php
namespace WeChat\WeChat\OpenPlatform;

use Doctrine\Common\Cache\Cache;
use WeChat\WeChat\Core\AccessToken as WechatAccessToken;
use WeChat\WeChat\Core\Exceptions\HttpException;
use WeChat\WeChat\OpenPlatform\Traits\VerifyTicketTrait;

class AccessToken extends WechatAccessToken
{
    use VerifyTicketTrait;

    /**
     * API.
     */
    const API_TOKEN_GET = 'https://api.weixin.qq.com/cgi-bin/component/api_component_token';

    /**
     * {@inheritdoc}.
     */
    protected $queryName = 'component_access_token';

    /**
     * {@inheritdoc}.
     */
    protected $tokenJsonKey = 'component_access_token';

    /**
     * {@inheritdoc}.
     */
    protected $prefix = 'wechat.open_platform.component_access_token.';

    /**
     * AccessToken constructor.
     *
     * @param string $appId
     * @param string $secret
     * @param Cache $cache
     * @param VerifyTicket $verifyTicket
     */
    public function __construct($appId, $secret, VerifyTicket $verifyTicket, Cache $cache = null)
    {
        parent::__construct($appId, $secret, $cache);

        $this->setVerifyTicket($verifyTicket);
    }

    /**
     * {@inheritdoc}.
     * @throws \Exception | \WeChat\WeChat\Core\Exceptions\RuntimeException
     */
    public function getTokenFromServer()
    {
        $data = [
            'component_appid' => $this->appId,
            'component_appsecret' => $this->secret,
            'component_verify_ticket' => $this->verifyTicket->getTicket(),
        ];
        $http = $this->getHttp();
        $token = $http->parseJSON($http->json(self::API_TOKEN_GET, $data));
        if (empty($token[$this->tokenJsonKey])) {
            throw new HttpException('Request ComponentAccessToken fail. response: ' . json_encode($token, JSON_UNESCAPED_UNICODE));
        }
        return $token;
    }
}
