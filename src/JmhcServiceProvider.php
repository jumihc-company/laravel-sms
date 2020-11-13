<?php
/**
 * User: YL
 * Date: 2020/07/01
 */

namespace Jmhc\SmsHelper;

use Illuminate\Support\ServiceProvider;

class JmhcServiceProvider extends ServiceProvider
{
    /**
     * @var string
     */
    protected $smsConfigPath;

    public function boot()
    {
        $this->smsConfigPath = __DIR__ . '/../config/jmhc-sms.php';

        // 合并配置
        $this->mergeConfig();

        // 发布文件
        $this->publishFiles();
    }

    /**
     * 合并配置
     */
    protected function mergeConfig()
    {
        // 合并 sms 配置
        $this->mergeConfigFrom(
            $this->smsConfigPath,
            'jmhc-sms'
        );
    }

    /**
     * 发布文件
     */
    protected function publishFiles()
    {
        // 发布配置文件
        $this->publishes([
            $this->smsConfigPath => config_path('jmhc-sms.php'),
        ], 'jmhc-sms');
    }
}
