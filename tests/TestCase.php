<?php

declare(strict_types=1);

namespace Tests;

use App\Bootstrap\Builder;
use DI\Container;
use Dotenv\Dotenv;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase as BaseTestCase;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\NamespacedPoolInterface;

class TestCase extends BaseTestCase
{
    /** Path to test files directory. */
    public const string TEST_FILES_PATH = __DIR__ . '/_files';

    protected Container $container;
    protected CacheInterface&NamespacedPoolInterface $cache;

    protected function setUp(): void
    {
        parent::setUp();

        putenv('COMPILE_CONTAINER=false');

        Dotenv::createUnsafeImmutable(__DIR__)->safeLoad();

        $this->container = Builder::createContainer(
            dirname(__DIR__) . '/config',
            $this->filePath('cache')
        );

        $this->container->set('posts_path', $this->filePath('data/posts'));
        $this->container->set('pages_path', $this->filePath('data/pages'));
        $this->container->set('cache_driver', 'array');

        $this->cache = $this->container->get(CacheInterface::class);
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
