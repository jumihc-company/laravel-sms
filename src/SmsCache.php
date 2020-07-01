<?php
/**
 * User: YL
 * Date: 2020/07/01
 */

namespace Jmhc\SmsHelper;

use Jmhc\Sms\Contracts\CacheInterface;
use Jmhc\Support\Traits\InstanceTrait;
use Jmhc\Support\Traits\RedisHandlerTrait;
use Jmhc\Support\Utils\ContainerHelper;

class SmsCache implements CacheInterface
{
    use InstanceTrait;
    use RedisHandlerTrait;

    /**
     * @var Connection|\Redis
     */
    protected $redis;

    public function __construct()
    {
        $this->redis = $this->getPhpRedisHandler();
    }

    /**
     * @inheritDoc
     */
    public function get(string $key): array
    {
        return $this->redis->hGetAll($key);
    }

    /**
     * @inheritDoc
     */
    public function set(string $key, array $data): bool
    {
        return $this->redis->hMSet($key, $data);
    }

    /**
     * @inheritDoc
     */
    public function expire(string $key, int $ttl): bool
    {
        return $this->redis->expire($key, $ttl);
    }

    /**
     * @inheritDoc
     */
    public function exists(string $key): bool
    {
        return !! $this->redis->exists($key);
    }

    /**
     * @inheritDoc
     */
    public function del(string $key): bool
    {
        return !! $this->redis->del($key);
    }

    /**
     * @inheritDoc
     */
    public function lock(string $key): bool
    {
        return $this->redis->command('set', [
            $key,
            1,
            [
                'nx',
                'ex' => ContainerHelper::config('jmhc-sms.send_lock_seconds', 5),
            ]
        ]);
    }

    /**
     * @inheritDoc
     */
    public function unlock(string $key): bool
    {
        return $this->del($key);
    }
}
