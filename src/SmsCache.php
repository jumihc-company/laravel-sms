<?php
/**
 * User: YL
 * Date: 2020/07/01
 */

namespace Jmhc\SmsHelper;

use Illuminate\Redis\Connections\PhpRedisConnection;
use Jmhc\Sms\Contracts\CacheInterface;
use Jmhc\Support\Helper\RedisConnectionHelper;
use Jmhc\Support\Traits\InstanceTrait;

class SmsCache implements CacheInterface
{
    use InstanceTrait;

    /**
     * @var PhpRedisConnection
     */
    protected $connection;

    public function __construct()
    {
        $this->connection = RedisConnectionHelper::getPhpRedis();
    }

    /**
     * @inheritDoc
     */
    public function get(string $key): array
    {
        return $this->connection->hGetAll($key);
    }

    /**
     * @inheritDoc
     */
    public function set(string $key, array $data): bool
    {
        return $this->connection->hMSet($key, $data);
    }

    /**
     * @inheritDoc
     */
    public function expire(string $key, int $ttl): bool
    {
        return $this->connection->expire($key, $ttl);
    }

    /**
     * @inheritDoc
     */
    public function exists(string $key): bool
    {
        return !! $this->connection->exists($key);
    }

    /**
     * @inheritDoc
     */
    public function del(string $key): bool
    {
        return !! $this->connection->del($key);
    }

    /**
     * @inheritDoc
     */
    public function lock(string $key): bool
    {
        return $this->connection->command('set', [
            $key,
            1,
            [
                'nx',
                'ex' => config('jmhc-sms.send_lock_seconds', 5),
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
