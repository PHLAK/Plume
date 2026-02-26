<?php

declare(strict_types=1);

namespace App\Commands;

use App\Tags;
use DI\Attribute\Inject;
use Symfony\Component\Cache\Adapter\AbstractAdapter;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\Cache\CacheInterface;

#[AsCommand(
    name: 'publish:tags',
    description: 'Publish all tags',
    help: 'Coming soon...',
)]
class PublishTags extends Command
{
    /** @var AbstractAdapter $cache */
    #[Inject(CacheInterface::class)]
    private CacheInterface $cache;

    #[Inject(Tags::class)]
    private Tags $tags;

    public function __invoke(OutputInterface $output): int
    {
        if ($this->cache instanceof ArrayAdapter) {
            $output->writeln("<fg=yellow>This command has no affect when using the 'array' cache driver</>");

            return self::FAILURE;
        }

        $output->write('Clearing tags cache ... ');
        $this->cache->withSubNamespace('tags')->clear();
        $this->cache->withSubNamespace('posts')->withSubNamespace('with-tag')->clear();
        $output->writeln('<fg=green>DONE</>');

        $output->write('Publishing all tags ... ');
        $tags = $this->tags->withCount();
        $output->writeln('<fg=green>DONE</>');

        $output->writeln(sprintf('<fg=green>%d tags published successfully</>', $tags->count()));

        return Command::SUCCESS;
    }
}
