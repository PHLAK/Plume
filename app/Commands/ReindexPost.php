<?php

declare(strict_types=1);

namespace App\Commands;

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
class ReindexPost extends Command
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
        $post = $this->posts->get($slug);
        $indexer = $this->search->getIndex('posts');

        if (! $indexer instanceof Indexer) {
            $output->writeln('<fg=red>No posts have been indexed yet</>');

            return Command::FAILURE;
        }

        $output->write('Updating post search index ... ');
        $indexer->update([
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
        ]);
        $output->writeln('<fg=green>DONE</>');

        $output->writeln(sprintf('<fg=green>"%s" reindexed successfully</>', $post->title));

        return Command::SUCCESS;
    }
}
