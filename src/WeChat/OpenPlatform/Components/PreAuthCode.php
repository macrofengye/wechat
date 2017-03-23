<?php
namespace WeChat\WeChat\OpenPlatform\Components;

use WeChat\WeChat\Core\Exceptions\InvalidArgumentException;
use WeChat\WeChat\Core\Exceptions\RuntimeException;
use WeChat\WeChat\Support\Url;

class PreAuthCode extends AbstractComponent
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
     * Redirect Uri.
     *
     * @var string
     */
    protected $redirectUri;


    /**
     * Get pre auth code.
     *
     * @throws InvalidArgumentException
     * @throws \WeChat\WeChat\Core\Exception
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
     * Get Redirect url.
     *
     * @return string
     *
     * @throws RuntimeException
     */
    public function getRedirectUri()
    {
        if (!$this->redirectUri) {
            throw new RuntimeException('You need to provided a redirect uri.');
        }
        return $this->redirectUri;
    }

    /**
     * Set redirect uri.
     *
     * @param string $uri
     *
     * @return $this
     */
    public function setRedirectUri($uri)
    {
        $this->redirectUri = $uri;
        return $this;
    }

    /**
     * Get auth page link.
     * @throws \Exception
     * @return string
     */
    public function getAuthLink()
    {
        return sprintf(self::PRE_AUTH_LINK,
            $this->getAppId(),
            $this->getCode(),
            urlencode($this->getRedirectUri())
        );
    }
}
