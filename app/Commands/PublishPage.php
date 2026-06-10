<?php

declare(strict_types=1);

namespace App\Commands;

use App\Data\Page;
use App\Pages;
use DI\Attribute\Inject;
use Symfony\Component\Cache\Adapter\AbstractAdapter;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Contracts\Cache\CacheInterface;

#[AsCommand(
    name: 'publish:page',
    description: 'Publish a page by slug',
)]
class PublishPage extends BaseCommand
{
    /** @var AbstractAdapter $cache */
    #[Inject(CacheInterface::class)]
    private CacheInterface $cache;

    #[Inject(Pages::class)]
    private Pages $pages;

    public function __invoke(
        Application $application,
        #[Argument('The page slug')] string $slug,
    ): int {
        if ($this->cache instanceof ArrayAdapter) {
            $this->error("This command has no affect when using the 'array' cache driver");

            return self::FAILURE;
        }

        $this->start(sprintf('Publishing the <fg=cyan>%s</> page', $slug));
        $this->process('Clearing page cache', fn (): bool => $this->cache->withSubNamespace('pages')->delete(hash('xxh128', $slug)));
        $page = $this->process('Rebuilding page cache', fn (): Page => $this->pages->get($slug));
        $this->success(sprintf('Published <fg=magenta>%s</> successfully</>', $page->title));

        $this->newLine();

        $application->doRun(new StringInput(sprintf('reindex:page %s', $slug)), $this->output);

        return self::SUCCESS;
    }
}
