<?php

namespace WeChat\WeChat\MiniProgram\QRCode;

use WeChat\WeChat\MiniProgram\Core\AbstractMiniProgram;

class QRCode extends AbstractMiniProgram
{
    /**
     * API.
     */
    const API_CREATE_QRCODE = 'https://api.weixin.qq.com/cgi-bin/wxaapp/createwxaqrcode';

    /**
     * Create mini program qrcode.
     *
     * @param $path
     * @param int $width
     *
     * @return \WeChat\WeChat\Support\Collection
     */
    public function create($path, $width = 430)
    {
        return $this->parseJSON('JSON', [self::API_CREATE_QRCODE, compact('path', 'width')]);
    }
}
