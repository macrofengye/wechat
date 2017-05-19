<?php

namespace WeChat\WeChat\OpenPlatform\Api;

use WeChat\WeChat\Core\Exceptions\InvalidArgumentException;
use Slim\Http\Response;

class PreAuthorization extends AbstractOpenPlatform
{
    /**
     * Create pre auth code url.
     */
    const CREATE_PRE_AUTH_CODE = 'https://api.weixin.qq.com/cgi-bin/component/api_create_preauthcode';

    /**
     * Pre auth link.
     */
    const PRE_AUTH_LINK = 'https://mp.weixin.qq.com/cgi-bin/componentloginpage?component_appid=%s&pre_auth_code=%s&redirect_uri=%s';

    /**
     * Get pre auth code.
     *
     * @throws \WeChat\WeChat\Core\Exceptions\InvalidArgumentException
     *
     * @return string
     */
    public function getCode()
    {
        $data = [
            'component_appid' => $this->getAppId(),
        ];

        $result = $this->parseJSON('json', [self::CREATE_PRE_AUTH_CODE, $data]);

        if (empty($result['pre_auth_code'])) {
            throw new InvalidArgumentException('Invalid response.');
        }

        return $result['pre_auth_code'];
    }

    /**
     * Redirect to WeChat PreAuthorization page.
     *
     * @param string $url
     *
     * @return \Slim\Http\Response
     */
    public function redirect($url)
    {
        return new RedirectResponse(
            sprintf(self::PRE_AUTH_LINK, $this->getAppId(), $this->getCode(), urlencode($url))
        );
    }
}
