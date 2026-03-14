<?php

declare(strict_types=1);

namespace App\Commands;

use App\Pages;
use DI\Attribute\Inject;
use Symfony\Component\Cache\Adapter\AbstractAdapter;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\Cache\CacheInterface;

#[AsCommand(
    name: 'publish:page',
    description: 'Publish a page by slug',
    help: 'Coming soon...',
)]
class PublishPage extends Command
{
    /** @var AbstractAdapter $cache */
    #[Inject(CacheInterface::class)]
    private CacheInterface $cache;

    #[Inject(Pages::class)]
    private Pages $pages;

    public function __invoke(
        OutputInterface $output,
        #[Argument('The page slug')] string $slug,
    ): int {
        if ($this->cache instanceof ArrayAdapter) {
            $output->writeln("<fg=yellow>This command has no affect when using the 'array' cache driver</>");

            return self::FAILURE;
        }

        $output->write('Clearing page cache ... ');
        $this->cache->withSubNamespace('pages')->delete(hash('xxh128', $slug));
        $output->writeln('<fg=green>DONE</>');

        $output->write('Publishing page ... ');
        $page = $this->pages->get($slug);
        $output->writeln('<fg=green>DONE</>');

        $output->writeln(sprintf('<fg=green>"%s" published successfully</>', $page->title));

        return self::SUCCESS;
    }
}
