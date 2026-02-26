<?php

declare(strict_types=1);

namespace App\Commands;

use App\Authors;
use DI\Attribute\Inject;
use Symfony\Component\Cache\Adapter\AbstractAdapter;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\Cache\CacheInterface;

#[AsCommand(
    name: 'publish:authors',
    description: 'Publish all authors',
    help: 'Coming soon...',
)]
class PublishAuthors extends Command
{
    /** @var AbstractAdapter $cache */
    #[Inject(CacheInterface::class)]
    private CacheInterface $cache;

    #[Inject(Authors::class)]
    private Authors $authors;

    public function __invoke(OutputInterface $output): int
    {
        if ($this->cache instanceof ArrayAdapter) {
            $output->writeln("<fg=yellow>This command has no affect when using the 'array' cache driver</>");

            return self::FAILURE;
        }

        $output->write('Clearing authors cache ... ');
        $this->cache->withSubNamespace('authors')->clear();
        $this->cache->withSubNamespace('posts')->withSubNamespace('by-author')->clear();
        $output->writeln('<fg=green>DONE</>');

        $output->write('Publishing all authors ... ');
        $authors = $this->authors->withCount();
        $output->writeln('<fg=green>DONE</>');

        $output->writeln(sprintf('<fg=green>%d authors published successfully</>', $authors->count()));

        return Command::SUCCESS;
    }
}
