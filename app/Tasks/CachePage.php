<?php

declare(strict_types=1);

namespace App\Tasks;

use App\Bootstrap\Builder;
use App\Data\Page;
use App\Pages;
use DI\Container;
use Spatie\Async\Task;

class CachePage extends Task
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
        $this->container->call(fn (Pages $pages): Page => $pages->get($this->slug));

        return $this->slug;
    }
}
