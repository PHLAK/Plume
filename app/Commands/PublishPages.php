<?php

declare(strict_types=1);

namespace App\Commands;

use App\Tasks\CachePage;
use DI\Attribute\Inject;
use GlobIterator;
use Spatie\Async\Pool;
use SplFileInfo;
use Symfony\Component\Cache\Adapter\AbstractAdapter;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\Cache\CacheInterface;

#[AsCommand('publish:pages', description: 'Publish all pages')]
class PublishPages extends Command
{
    /** @var AbstractAdapter $cache */
    #[Inject(CacheInterface::class)]
    private CacheInterface $cache;

    #[Inject('pages_path')]
    private string $pagesPath;

    public function __invoke(OutputInterface $output): int
    {
        if ($this->cache instanceof ArrayAdapter) {
            $output->writeln("<fg=yellow>This command has no affect when using the 'array' cache driver</>");

            return self::FAILURE;
        }

        $output->write('Clearing pages cache ... ');
        $this->cache->withSubNamespace('pages')->clear();
        $output->writeln('<fg=green>DONE</>');

        $output->write('Publishing all pages ... ');
        $slugs = $this->cachePages();
        $output->writeln('<fg=green>DONE</>');

        $output->writeln(sprintf('<fg=green>%d pages published successfully</>', count($slugs)));

        return Command::SUCCESS;
    }

    /** @return array<string, SplFileInfo> */
    private function cachePages(): array
    {
        /** @var iterable<string, SplFileInfo> */
        $pages = new GlobIterator($this->pagesPath . '/*.md');

        $slugs = [];
        foreach ($pages as $page) {
            $slugs[] = $page->getBasename('.md');
        }

        $pool = Pool::create();

        foreach ($slugs as $slug) {
            $pool->add(new CachePage($slug));
        }

        return $pool->wait();
    }
}
