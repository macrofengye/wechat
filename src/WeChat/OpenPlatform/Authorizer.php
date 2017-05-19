<?php

namespace WeChat\WeChat\OpenPlatform;

use Doctrine\Common\Cache\Cache;
use WeChat\WeChat\Core\Exception;
use WeChat\WeChat\OpenPlatform\Api\BaseApi;

class Authorizer
{
    const CACHE_KEY_ACCESS_TOKEN = 'easywechat.open_platform.authorizer_access_token';
    const CACHE_KEY_REFRESH_TOKEN = 'easywechat.open_platform.authorizer_refresh_token';

    /**
     * Cache.
     *
     * @var \Doctrine\Common\Cache\Cache
     */
    protected $cache;

    /**
     * Base API.
     *
     * @var \WeChat\WeChat\OpenPlatform\Api\BaseApi
     */
    protected $api;

    /**
     * Authorizer AppId.
     *
     * @var string
     */
    protected $appId;

    /**
     * OpenPlatform AppId.
     *
     * @var string
     */
    protected $openPlatformAppId;

    /**
     * Authorizer Constructor.
     *
     * @param \WeChat\WeChat\OpenPlatform\Api\BaseApi $api
     * @param string $openPlatformAppId OpenPlatform AppId
     * @param \Doctrine\Common\Cache\Cache $cache
     */
    public function __construct(BaseApi $api, $openPlatformAppId, Cache $cache)
    {
        $this->api = $api;
        $this->openPlatformAppId = $openPlatformAppId;
        $this->cache = $cache;
    }

    /**
     * Gets the base api.
     *
     * @return \WeChat\WeChat\OpenPlatform\Api\BaseApi
     */
    public function getApi()
    {
        return $this->api;
    }

    /**
     * Sets the authorizer app id.
     *
     * @param string $appId
     *
     * @return $this
     */
    public function setAppId($appId)
    {
        $this->appId = $appId;

        return $this;
    }

    /**
     * Gets the authorizer app id, or throws if not found.
     *
     * @return string
     *
     * @throws \WeChat\WeChat\Core\Exception
     */
    public function getAppId()
    {
        if (!$this->appId) {
            throw new Exception(
                'Authorizer App Id is not present, you may not make the authorizer yet.'
            );
        }

        return $this->appId;
    }

    /**
     * Saves the authorizer access token in cache.
     *
     * @param string $token
     * @param int $expires
     *
     * @return $this
     */
    public function setAccessToken($token, $expires = 7200)
    {
        $this->cache->save($this->getAccessTokenCacheKey(), $token, $expires);

        return $this;
    }

    /**
     * Gets the authorizer access token.
     *
     * @return string
     */
    public function getAccessToken()
    {
        return $this->cache->fetch($this->getAccessTokenCacheKey());
    }

    /**
     * Saves the authorizer refresh token in cache.
     *
     * @param string $refreshToken
     *
     * @return $this
     */
    public function setRefreshToken($refreshToken)
    {
        $this->cache->save($this->getRefreshTokenCacheKey(), $refreshToken);

        return $this;
    }

    /**
     * Gets the authorizer refresh token.
     *
     * @return string
     *
     * @throws \WeChat\WeChat\Core\Exception when refresh token is not present
     */
    public function getRefreshToken()
    {
        if ($token = $this->cache->fetch($this->getRefreshTokenCacheKey())) {
            return $token;
        }

        throw new Exception(
            'Authorizer Refresh Token is not present, you may not make the authorizer yet.'
        );
    }

    /**
     * Gets the authorizer access token cache key.
     *
     * @return string
     */
    public function getAccessTokenCacheKey()
    {
        return self::CACHE_KEY_ACCESS_TOKEN . $this->appId . $this->getAppId();
    }

    /**
     * Gets the authorizer refresh token cache key.
     *
     * @return string
     */
    public function getRefreshTokenCacheKey()
    {
        return self::CACHE_KEY_REFRESH_TOKEN . $this->appId . $this->getAppId();
    }
}
