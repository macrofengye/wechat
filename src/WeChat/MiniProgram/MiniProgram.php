<?php
namespace WeChat\WeChat\MiniProgram;

use WeChat\WeChat\Support\Traits\PrefixedContainer;

/**
 * Class MiniProgram.
 *
 * @property \WeChat\WeChat\MiniProgram\Server\Guard $server
 * @property \WeChat\WeChat\MiniProgram\Sns\Sns $sns
 * @property \WeChat\WeChat\MiniProgram\Notice\Notice $notice
 * @property \WeChat\WeChat\MiniProgram\Staff\Staff $staff
 * @property \WeChat\WeChat\MiniProgram\QRCode\QRCode $qrcode
 * @property \WeChat\WeChat\MiniProgram\Material\Temporary $material_temporary
 * @property \WeChat\WeChat\MiniProgram\Stats\Stats $stats
 */
class MiniProgram
{
    use PrefixedContainer;
}
