<?php

namespace WeChat\WeChat\OpenPlatform;

use WeChat\WeChat\Support\Traits\PrefixedContainer;

/**
 * Class OpenPlatform.
 *
 * @property \WeChat\WeChat\OpenPlatform\Api\BaseApi $api
 * @property \WeChat\WeChat\OpenPlatform\Api\PreAuthorization $pre_auth
 * @property \WeChat\WeChat\OpenPlatform\Guard $server
 * @property \WeChat\WeChat\OpenPlatform\AccessToken $access_token
 *
 * @method \WeChat\WeChat\Support\Collection getAuthorizationInfo($authCode = null)
 * @method \WeChat\WeChat\Support\Collection getAuthorizerInfo($authorizerAppId)
 * @method \WeChat\WeChat\Support\Collection getAuthorizerOption($authorizerAppId, $optionName)
 * @method \WeChat\WeChat\Support\Collection setAuthorizerOption($authorizerAppId, $optionName, $optionValue)
 */
class OpenPlatform
{
    use PrefixedContainer;

    /**
     * Create an instance of the WeChat for the given authorizer.
     *
     * @param string $appId        Authorizer AppId
     * @param string $refreshToken Authorizer refresh-token
     *
     * @return \WeChat\WeChat\Foundation\Application
     */
    public function createAuthorizerApplication($appId, $refreshToken)
    {
        $this->fetch('authorizer', function ($authorizer) use ($appId, $refreshToken) {
            $authorizer->setAppId($appId);
            $authorizer->setRefreshToken($refreshToken);
        });

        return $this->fetch('app', function ($app) {
            $app['access_token'] = $this->fetch('authorizer_access_token');
            $app['oauth'] = $this->fetch('oauth');
            $app['server'] = $this->fetch('server');
        });
    }

    /**
     * Quick access to the base-api.
     *
     * @param string $method
     * @param array  $args
     *
     * @return mixed
     */
    public function __call($method, $args)
    {
        return call_user_func_array([$this->api, $method], $args);
    }
}
