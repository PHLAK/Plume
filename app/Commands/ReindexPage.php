<?php

declare(strict_types=1);

namespace App\Commands;

use App\Pages;
use DI\Attribute\Inject;
use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;
use YetiSearch\Index\Indexer;
use YetiSearch\YetiSearch;

#[AsCommand('reindex:page', description: 'Update the search index for a single page')]
class ReindexPage extends Command
{
    #[Inject(Pages::class)]
    private Pages $pages;

    #[Inject(YetiSearch::class)]
    private YetiSearch $search;

    public function __invoke(
        OutputInterface $output,
        #[Argument('The page slug')] string $slug,
    ): int {
        $page = $this->pages->get($slug);
        $indexer = $this->search->getIndex('pages');

        if (! $indexer instanceof Indexer) {
            $output->writeln('<fg=red>No pages have been indexed yet</>');

            return Command::FAILURE;
        }

        $output->write('Updating page search index ... ');
        $indexer->update([
            'id' => $slug,
            'content' => [
                'title' => $page->title,
                'link' => $page->link,
                'body' => $page->body,
            ],
            'type' => 'page',
        ]);
        $output->writeln('<fg=green>DONE</>');

        $output->writeln(sprintf('<fg=green>"%s" reindexed successfully</>', $page->title));

        return Command::SUCCESS;
    }
}
