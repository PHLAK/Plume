<?php

declare(strict_types=1);

namespace App\Tasks;

use App\Bootstrap\Builder;
use App\Data\Post;
use App\Posts;
use DI\Container;
use Spatie\Async\Task;

class CachePost extends Task
{
    private Container $container;

    public function __construct(
        private string $slug
    ) {}

    public function configure(): void
    {
        $this->container = Builder::createContainer(
            dirname(__DIR__, 2) . '/config',
            dirname(__DIR__, 2) . '/cache'
        );
    }

    public function run(): string
    {
        $this->container->call(fn (Posts $posts): Post => $posts->get($this->slug));

        return $this->slug;
    }
}
