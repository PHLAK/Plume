<?php

declare(strict_types=1);

namespace App\Commands;

use App\Pages;
use DI\Attribute\Inject;
use Symfony\Component\Cache\Adapter\AbstractAdapter;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\Cache\CacheInterface;

#[AsCommand(
    name: 'publish:pages',
    description: 'Publish all pages',
    help: 'Coming soon...',
)]
class PublishPages extends Command
{
    /** @var AbstractAdapter $cache */
    #[Inject(CacheInterface::class)]
    private CacheInterface $cache;

    #[Inject(Pages::class)]
    private Pages $pages;

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
        $pages = $this->pages->all();
        $count = $pages->count();
        $output->writeln('<fg=green>DONE</>');

        $output->writeln(sprintf('<fg=green>%d pages published successfully</>', $count));

        return Command::SUCCESS;
    }
}
