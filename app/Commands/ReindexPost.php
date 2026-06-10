<?php

declare(strict_types=1);

namespace App\Commands;

use App\Exceptions\NotFoundException;
use App\Posts;
use DI\Attribute\Inject;
use Slim\Interfaces\RouteParserInterface;
use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;
use YetiSearch\Index\Indexer;
use YetiSearch\YetiSearch;

#[AsCommand('reindex:post', description: 'Update the search index for a single post')]
class ReindexPost extends BaseCommand
{
    #[Inject(Posts::class)]
    private Posts $posts;

    #[Inject(YetiSearch::class)]
    private YetiSearch $search;

    #[Inject(RouteParserInterface::class)]
    private RouteParserInterface $routeParser;

    public function __invoke(
        OutputInterface $output,
        #[Argument('The post slug')] string $slug,
    ): int {
        try {
            $post = $this->posts->get($slug);
        } catch (NotFoundException) {
            $this->error(sprintf('No post with slug <fg=cyan>%s</> found', $slug));

            return Command::FAILURE;
        }

        $indexer = $this->search->getIndex('posts');

        if (! $indexer instanceof Indexer) {
            $this->error('No posts have been indexed yet');

            return Command::FAILURE;
        }

        $this->start(sprintf('Updatting the <fg=cyan>%s</> post search index', $slug));

        $this->process('Updating post search index', fn () => $indexer->update([
            'id' => $slug,
            'content' => [
                'title' => $post->title,
                'body' => $post->bodyForIndex(),
                'tags' => implode(' ', $post->tags),
            ],
            'metadata' => [
                'url' => $this->routeParser->urlFor('post', ['slug' => $slug]),
                'published' => $post->published->toDateTimeString(),
            ],
            'type' => 'post',
        ]));

        $this->success(sprintf('Reindexed <fg=magenta>%s</> successfully</>', $post->title));

        return Command::SUCCESS;
    }
}
