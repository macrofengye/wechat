<?php

namespace WeChat\WeChat\OpenPlatform;

use WeChat\WeChat\Core\AccessToken as CoreAccessToken;
use WeChat\WeChat\Core\Exceptions\HttpException;

class AccessToken extends CoreAccessToken
{
    /**
     * VerifyTicket.
     *
     * @var \WeChat\WeChat\OpenPlatform\VerifyTicket
     */
    protected $verifyTicket;

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
    protected $prefix = 'macro.wechat.open_platform.component_access_token.';

    /**
     * Set VerifyTicket.
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
     * {@inheritdoc}.
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
            throw new HttpException('Request ComponentAccessToken fail. response: '.json_encode($token, JSON_UNESCAPED_UNICODE));
        }

        return $token;
    }
}
