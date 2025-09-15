<?php

declare(strict_types=1);

namespace App\Factories;

use App\Config;
use App\Exceptions\InvalidConfiguration;
use DI\Container;
use Memcached;
use Redis;
use Symfony\Component\Cache\Adapter\ApcuAdapter;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Adapter\MemcachedAdapter;
use Symfony\Component\Cache\Adapter\PhpFilesAdapter;
use Symfony\Component\Cache\Adapter\RedisAdapter;
use Symfony\Contracts\Cache\CacheInterface;

class CacheFactory
{
    private const NAMESPACE_EXTERNAL = 'plume_php';
    private const NAMESPACE_INTERNAL = 'app';

    public function __construct(
        private Container $container,
        private Config $config
    ) {}

    public function __invoke(): CacheInterface
    {
        return match ($this->config->string('cache_driver')) {
            'apcu' => $this->getApcuAdapter(),
            'array' => $this->getArrayAdapter(),
            'file' => $this->getFilesystemAdapter(),
            'memcached' => $this->getMemcachedAdapter(),
            'php-file' => $this->getPhpFilesAdapter(),
            'redis' => $this->getRedisAdapter(),
            'valkey' => $this->getRedisAdapter(),
            default => throw InvalidConfiguration::fromOption('cache_driver', $this->config->string('cache_driver'))
        };
    }

    private function getApcuAdapter(): ApcuAdapter
    {
        return new ApcuAdapter(self::NAMESPACE_EXTERNAL, $this->config->integer('cache_lifetime'));
    }

    private function getArrayAdapter(): ArrayAdapter
    {
        return new ArrayAdapter($this->config->integer('cache_lifetime'));
    }

    private function getFilesystemAdapter(): FilesystemAdapter
    {
        return new FilesystemAdapter(
            self::NAMESPACE_INTERNAL,
            $this->config->integer('cache_lifetime'),
            $this->config->string('cache_path')
        );
    }

    private function getMemcachedAdapter(): MemcachedAdapter
    {
        $this->container->call('memcached_config', [$memcached = new Memcached]);

        return new MemcachedAdapter(
            $memcached,
            self::NAMESPACE_EXTERNAL,
            $this->config->integer('cache_lifetime')
        );
    }

    private function getPhpFilesAdapter(): PhpFilesAdapter
    {
        return new PhpFilesAdapter(
            self::NAMESPACE_INTERNAL,
            $this->config->integer('cache_lifetime'),
            $this->config->string('cache_path')
        );
    }

    private function getRedisAdapter(): RedisAdapter
    {
        $this->container->call('redis_config', [$redis = new Redis]);

        return new RedisAdapter(
            $redis,
            self::NAMESPACE_EXTERNAL,
            $this->config->integer('cache_lifetime')
        );
    }
}
