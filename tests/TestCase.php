<?php

declare(strict_types=1);

namespace Tests;

use App\Bootstrap\Builder;
use App\Config;
use DI\Container;
use Dotenv\Dotenv;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase as BaseTestCase;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Contracts\Cache\CacheInterface;

class TestCase extends BaseTestCase
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

        $this->container = Builder::createContainer(
            dirname(__DIR__) . '/config',
            $this->filePath('cache')
        );

        $this->config = $this->container->get(Config::class);
        $this->cache = new ArrayAdapter;

        $this->container->set('posts_path', $this->filePath('posts'));

        Builder::createApp($this->container);
    }

    /** Get the file path to a test file. */
    protected function filePath(string $path): string
    {
        return sprintf('%s/%s', self::TEST_FILES_PATH, $path);
    }

    /** Get the contents of a test file. */
    protected function fileContents(string $path): string
    {
        return file_get_contents($this->filePath($path));
    }

    /**
     * @template TClass of object
     *
     * @param class-string<TClass> $className
     *
     * @return TClass&MockObject
     */
    protected function mock(string $className): mixed
    {
        $mock = $this->createMock($className);

        $this->container->set($className, $mock);

        return $mock;
    }
}
