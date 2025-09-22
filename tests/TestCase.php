<?php

declare(strict_types=1);

namespace Tests;

use App\Bootstrapper;
use App\Config;
use DI\Container;
use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase as FrameworkTestCase;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Contracts\Cache\CacheInterface;

class TestCase extends FrameworkTestCase
{
    /** Path to test files directory. */
    public const string TEST_FILES_PATH = __DIR__ . '/_files';

    protected Container $container;
    protected Config $config;
    protected CacheInterface $cache;

    protected function setUp(): void
    {
        parent::setUp();

        Dotenv::createUnsafeImmutable(__DIR__)->safeLoad();

        $this->container = Bootstrapper::createContainer(
            dirname(__DIR__) . '/config',
            dirname(__DIR__) . '/cache'
        );

        $this->config = new Config($this->container);
        $this->cache = new ArrayAdapter((int) $this->config->get('cache_lifetime'));

        $this->container->set('base_path', self::TEST_FILES_PATH);
        $this->container->set('cache_path', $this->filePath('app/cache'));
    }

    /** Get the file path to a test file. */
    protected function filePath(string $filePath): string
    {
        return sprintf('%s/%s', self::TEST_FILES_PATH, $filePath);
    }
}
