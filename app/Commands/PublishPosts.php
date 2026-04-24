<?php

declare(strict_types=1);

namespace App\Commands;

use App\Tasks\CachePost;
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

#[AsCommand('publish:posts', description: 'Publish all posts')]
class PublishPosts extends Command
{
    /** @var AbstractAdapter $cache */
    #[Inject(CacheInterface::class)]
    private CacheInterface $cache;

    #[Inject('posts_path')]
    private string $postsPath;

    public function __invoke(OutputInterface $output): int
    {
        if ($this->cache instanceof ArrayAdapter) {
            $output->writeln("<fg=yellow>This command has no affect when using the 'array' cache driver</>");

            return self::FAILURE;
        }

        $output->write('Clearing posts cache ... ');
        $this->cache->withSubNamespace('posts')->clear();
        $output->writeln('<fg=green>DONE</>');

        $output->write('Publishing all posts ... ');
        $slugs = $this->cachePosts();
        $output->writeln('<fg=green>DONE</>');

        $output->writeln(sprintf('<fg=green>%d posts published successfully</>', count($slugs)));

        return Command::SUCCESS;
    }

    private function cachePosts(): array
    {
        /** @var array<string, string> $slugs */
        $slugs = array_map(
            fn (SplFileInfo $file): string => $file->getBasename('.md'),
            iterator_to_array(new GlobIterator($this->postsPath . '/*.md'))
        );

        $pool = Pool::create();

        foreach ($slugs as $slug) {
            $pool->add(new CachePost($slug));
        }

        return $pool->wait();
    }
}
