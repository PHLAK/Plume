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
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Contracts\Cache\CacheInterface;

#[AsCommand('publish:posts', description: 'Publish all posts')]
class PublishPosts extends BaseCommand
{
    /** @var AbstractAdapter $cache */
    #[Inject(CacheInterface::class)]
    private CacheInterface $cache;

    #[Inject('posts_path')]
    private string $postsPath;

    public function __invoke(Application $application): int
    {
        if ($this->cache instanceof ArrayAdapter) {
            $this->error("This command has no affect when using the 'array' cache driver");

            return self::FAILURE;
        }

        $this->start('Publishing all posts');
        $this->process('Clearing posts cache', fn (): bool => $this->cache->withSubNamespace('posts')->clear());
        $slugs = $this->process('Rebuilding posts cache', $this->cachePosts(...));
        $this->success(sprintf('Published <fg=magenta>%d</> posts successfully', count($slugs)));

        $this->newLine();

        $application->doRun(new StringInput('reindex:posts'), $this->output);

        return Command::SUCCESS;
    }

    /** @return array<int, string> */
    private function cachePosts(): array
    {
        /** @var iterable<string, SplFileInfo> */
        $posts = new GlobIterator($this->postsPath . '/*.md');

        $slugs = [];
        foreach ($posts as $post) {
            $slugs[] = $post->getBasename('.md');
        }

        $pool = Pool::create();

        foreach ($slugs as $slug) {
            $pool->add(new CachePost($slug));
        }

        return $pool->wait();
    }
}
