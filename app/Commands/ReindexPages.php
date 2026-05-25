<?php

declare(strict_types=1);

namespace App\Commands;

use App\Data\Page;
use App\Pages;
use DI\Attribute\Inject;
use Slim\Interfaces\RouteParserInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;
use YetiSearch\Index\Indexer;
use YetiSearch\YetiSearch;

#[AsCommand('reindex:pages', description: 'Rebuild the pages search index')]
class ReindexPages extends Command
{
    #[Inject(Pages::class)]
    private Pages $pages;

    #[Inject(YetiSearch::class)]
    private YetiSearch $search;

    #[Inject(RouteParserInterface::class)]
    private RouteParserInterface $routeParser;

    public function __invoke(OutputInterface $output): int
    {
        $output->write('Deleting pages search index ... ');
        $this->search->dropIndex('pages');
        $output->writeln('<fg=green>DONE</>');

        $indexer = $this->search->createIndex('pages', [
            'fields' => [
                'title' => ['boost' => 5.0, 'store' => true],
                'body' => ['boost' => 1.0, 'store' => true],
            ],
        ]);

        $output->write('Rebuilding pages search index ... ');
        $slugs = $this->rebuildSearchIndex($indexer);
        $output->writeln('<fg=green>DONE</>');

        $output->writeln(sprintf('<fg=green>%d pages reindexed successfully</>', count($slugs)));

        return Command::SUCCESS;
    }

    /** @return array<int, string> */
    private function rebuildSearchIndex(Indexer $indexer): array
    {
        $pages = $this->pages->all()->each(
            fn (Page $page, string $slug) => $indexer->insert([
                'id' => $slug,
                'content' => [
                    'title' => $page->title,
                    'body' => $page->bodyForIndex(),
                ],
                'metadata' => [
                    'url' => $this->routeParser->urlFor('page', ['slug' => $slug]),
                ],
                'type' => 'page',
            ])
        );

        $indexer->flush();

        return $pages->keys()->all();
    }
}
