<?php

declare(strict_types=1);

namespace App\Commands;

use App\Data\Post;
use App\Posts;
use DI\Attribute\Inject;
use Symfony\Component\Cache\Adapter\AbstractAdapter;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Contracts\Cache\CacheInterface;

#[AsCommand(
    name: 'publish:post',
    description: 'Publish a post by slug',
)]
class PublishPost extends BaseCommand
{
    /** @var AbstractAdapter */
    #[Inject(CacheInterface::class)]
    private CacheInterface $cache;

    #[Inject(Posts::class)]
    private Posts $posts;

    public function __invoke(
        Application $application,
        #[Argument('The post slug')] string $slug,
    ): int {
        if ($this->cache instanceof ArrayAdapter) {
            $this->error("This command has no affect when using the 'array' cache driver");

            return self::FAILURE;
        }

        $this->start(sprintf('Publishing the <fg=cyan>%s</> post', $slug));
        $this->process('Clearing post cache', fn (): bool => $this->cache->withSubNamespace('posts')->delete(hash('xxh128', $slug)));
        $post = $this->process('Rebuilding post cache', fn (): Post => $this->posts->get($slug));
        $this->success(sprintf('Published <fg=magenta>%s</> successfully</>', $post->title));

        $this->newLine();

        $application->doRun(new StringInput(sprintf('reindex:post %s', $slug)), $this->output);

        return self::SUCCESS;
    }
}
