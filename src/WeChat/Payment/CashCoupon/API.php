<?php

namespace WeChat\WeChat\Payment\CashCoupon;

use WeChat\WeChat\Core\AbstractAPI;
use WeChat\WeChat\Payment\Merchant;
use WeChat\WeChat\Support\Collection;
use WeChat\WeChat\Support\XML;
use Psr\Http\Message\ResponseInterface;

/**
 * Class API.
 */
class API extends AbstractAPI
{
    /**
     * Merchant instance.
     *
     * @var Merchant
     */
    protected $merchant;

    // api
    const API_SEND = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/send_coupon';
    const API_QUERY_STOCK = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/query_coupon_stock';
    const API_QUERY = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/querycouponsinfo';

    /**
     * API constructor.
     *
     * @param \WeChat\WeChat\Payment\Merchant $merchant
     */
    public function __construct(Merchant $merchant)
    {
        $this->merchant = $merchant;
    }

    /**
     * send a cash coupon.
     *
     * @param array $params
     *
     * @return \WeChat\WeChat\Support\Collection
     */
    public function send(array $params)
    {
        $params['openid_count'] = 1;

        return $this->request(self::API_SEND, $params);
    }

    /**
     * query a coupon stock.
     *
     * @param array $params
     *
     * @return \WeChat\WeChat\Support\Collection
     */
    public function queryStock(array $params)
    {
        return $this->request(self::API_QUERY_STOCK, $params);
    }

    /**
     * query a info of coupon.
     *
     * @param array $params
     *
     * @return \WeChat\WeChat\Support\Collection
     */
    public function query(array $params)
    {
        return $this->request(self::API_QUERY, $params);
    }

    /**
     * Merchant setter.
     *
     * @param Merchant $merchant
     *
     * @return $this
     */
    public function setMerchant(Merchant $merchant)
    {
        $this->merchant = $merchant;
    }

    /**
     * Merchant getter.
     *
     * @return Merchant
     */
    public function getMerchant()
    {
        return $this->merchant;
    }

    /**
     * Make a API request.
     *
     * @param string $api
     * @param array  $params
     * @param string $method
     *
     * @return \WeChat\WeChat\Support\Collection
     */
    protected function request($api, array $params, $method = 'post')
    {
        $params = array_filter($params);
        $params['mch_id'] = $this->merchant->merchant_id;
        $params['appid'] = $this->merchant->app_id;
        $params['nonce_str'] = uniqid();
        $params['sign'] = \WeChat\WeChat\Payment\generate_sign($params, $this->merchant->key, 'md5');

        $options = [
            'body' => XML::build($params),
            'cert' => $this->merchant->get('cert_path'),
            'ssl_key' => $this->merchant->get('key_path'),
        ];

        return $this->parseResponse($this->getHttp()->request($api, $method, $options));
    }

    /**
     * Parse Response XML to array.
     *
     * @param \Psr\Http\Message\ResponseInterface|string $response
     *
     * @return \WeChat\WeChat\Support\Collection
     */
    protected function parseResponse($response)
    {
        if ($response instanceof ResponseInterface) {
            $response = $response->getBody();
        }

        return new Collection((array) XML::parse($response));
    }
}
