<?php

declare(strict_types=1);

namespace App\Commands;

use App\Data\Post;
use App\Posts;
use DI\Attribute\Inject;
use Slim\Interfaces\RouteParserInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;
use YetiSearch\Index\Indexer;
use YetiSearch\YetiSearch;

#[AsCommand('reindex:posts', description: 'Rebuild the posts search index')]
class ReindexPosts extends BaseCommand
{
    #[Inject(Posts::class)]
    private Posts $posts;

    #[Inject(YetiSearch::class)]
    private YetiSearch $search;

    #[Inject(RouteParserInterface::class)]
    private RouteParserInterface $routeParser;

    public function __invoke(OutputInterface $output): int
    {
        $this->start('Rebuilding posts search index');

        $this->process('Deleting posts search index', fn () => $this->search->dropIndex('posts'));

        $indexer = $this->process('Creating posts search index', fn (): Indexer => $this->search->createIndex('posts', [
            'fields' => [
                'title' => ['boost' => 5.0, 'store' => true],
                'body' => ['boost' => 1.0, 'store' => true],
                'tags' => ['boost' => 2.0, 'store' => true],
            ],
        ]));

        $slugs = $this->process('Reindexing posts', fn (): array => $this->rebuildSearchIndex($indexer));

        $this->success(sprintf('Reindexed <fg=magenta>%d</> posts successfully', count($slugs)));

        return Command::SUCCESS;
    }

    /** @return array<int, string> */
    private function rebuildSearchIndex(Indexer $indexer): array
    {
        $posts = $this->posts->all()->each(
            fn (Post $post, string $slug) => $indexer->insert([
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
            ])
        );

        $indexer->flush();

        return $posts->keys()->all();
    }
}
