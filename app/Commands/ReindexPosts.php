<?php

declare(strict_types=1);

namespace App\Commands;

use App\Data\Post;
use App\Posts;
use DI\Attribute\Inject;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;
use YetiSearch\Index\Indexer;
use YetiSearch\YetiSearch;

#[AsCommand('reindex:posts', description: 'Rebuild the posts search index')]
class ReindexPosts extends Command
{
    #[Inject(Posts::class)]
    private Posts $posts;

    #[Inject(YetiSearch::class)]
    private YetiSearch $search;

    public function __invoke(OutputInterface $output): int
    {
        $output->write('Deleting posts search index ... ');
        $this->search->dropIndex('posts');
        $output->writeln('<fg=green>DONE</>');

        $indexer = $this->search->createIndex('posts', [
            'fields' => [
                'title' => ['boost' => 3.0, 'store' => true],
                'author' => ['boost' => 2.0, 'store' => true],
                'body' => ['boost' => 1.0, 'store' => true],
                'tags' => ['boost' => 1.0, 'store' => true],
            ],
        ]);

        $output->write('Rebuilding posts search index ... ');
        $slugs = $this->rebuildSearchIndex($indexer);
        $output->writeln('<fg=green>DONE</>');

        $output->writeln(sprintf('<fg=green>%d posts reindexed successfully</>', count($slugs)));

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
                    'author' => $post->author,
                    'body' => $post->body,
                    'tags' => $post->tags,
                ],
                'metadata' => [
                    'published' => $post->published->toDateTimeString(),
                    'draft' => $post->draft,
                ],
                'type' => 'post',
            ])
        );

        $indexer->flush();

        return $posts->keys()->all();
    }
}
