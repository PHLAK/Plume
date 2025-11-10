<?php

declare(strict_types=1);

namespace App\Commands;

use App\Posts;
use DI\Attribute\Inject;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\Cache\CacheInterface;

#[AsCommand(
    name: 'publish:posts',
    description: 'Cache the posts lists, already cached posts will not be re-cached',
    help: 'Coming soon...',
)]
class PublishPosts extends Command
{
    #[Inject(CacheInterface::class)]
    private CacheInterface $cache;

    #[Inject(Posts::class)]
    private Posts $posts;

    public function __invoke(OutputInterface $output): int
    {
        if ($this->cache instanceof ArrayAdapter) {
            $output->writeln("<comment>This command has no affect when using the 'array' cache driver.</comment>");

            return self::INVALID;
        }

        $output->write('Clearing posts cache ... ');
        $this->cache->delete('all-posts');
        $output->writeln('<fg=green>DONE</>');

        $output->write('Publishing all posts ... ');
        $posts = $this->posts->all();
        $output->writeln('<fg=green>DONE</>');

        $output->writeln(sprintf('<fg=green>%d posts published successfully</>', $posts->count()));

        return Command::SUCCESS;
    }
}
