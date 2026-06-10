<?php

declare(strict_types=1);

namespace App\Commands;

use App\Exceptions\NotFoundException;
use App\Pages;
use DI\Attribute\Inject;
use Slim\Interfaces\RouteParserInterface;
use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;
use YetiSearch\Index\Indexer;
use YetiSearch\YetiSearch;

#[AsCommand('reindex:page', description: 'Update the search index for a single page')]
class ReindexPage extends BaseCommand
{
    #[Inject(Pages::class)]
    private Pages $pages;

    #[Inject(YetiSearch::class)]
    private YetiSearch $search;

    #[Inject(RouteParserInterface::class)]
    private RouteParserInterface $routeParser;

    public function __invoke(
        OutputInterface $output,
        #[Argument('The page slug')] string $slug,
    ): int {
        try {
            $page = $this->pages->get($slug);
        } catch (NotFoundException) {
            $this->error(sprintf('No page with slug <fg=cyan>%s</> found', $slug));

            return Command::FAILURE;
        }

        $indexer = $this->search->getIndex('pages');

        if (! $indexer instanceof Indexer) {
            $this->error('No pages have been indexed yet');

            return Command::FAILURE;
        }

        $this->start(sprintf('Updatting the <fg=cyan>%s</> page search index', $slug));

        $this->process('Updating page search index', fn () => $indexer->update([
            'id' => $slug,
            'content' => [
                'title' => $page->title,
                'body' => $page->bodyForIndex(),
            ],
            'metadata' => [
                'url' => $this->routeParser->urlFor('page', ['slug' => $slug]),
            ],
            'type' => 'page',
        ]));

        $this->success(sprintf('Reindexed <fg=magenta>%s</> successfully</>', $page->title));

        return Command::SUCCESS;
    }
}
