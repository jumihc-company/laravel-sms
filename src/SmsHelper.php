<?php
/**
 * User: YL
 * Date: 2020/07/01
 */

namespace Jmhc\SmsHelper;

use Jmhc\Sms\Sms;
use Jmhc\Support\Utils\ContainerHelper;

class SmsHelper
{
    /**
     * 获取发送短信实例
     * @return Sms
     */
    public static function getSms()
    {
        return new Sms(
            SmsCache::getInstance(),
            ContainerHelper::config('jmhc-sms', [])
        );
    }

    /**
     * 获取发送短信缓存
     * @return \Jmhc\Sms\Utils\SmsCache
     */
    public static function getSmsCache()
    {
        return new \Jmhc\Sms\Utils\SmsCache(SmsCache::getInstance());
    }
}
