<?php
if (!function_exists('weChatConfig')) {
    /**
     * 获取微信的配置信息
     *
     * @return array
     */
    function weChatConfig()
    {
        $request = app()->component('request');
        if (PHP_SAPI === 'cli') {
            $appId = $request->getServerParam('argc') >= 2 ? $request->getServerParam('argv')[1] : 0;
        } else {
            $appId = $request->getParam('app_id') ?: 0;
        }
        $Config = include __DIR__ . '/config.php';
        return isset($Config[$appId]) ? $Config[$appId] : [];
    }
}