<?php

declare(strict_types=1);

namespace App\Commands;

use DI\Attribute\Inject;
use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'view:clear-cache',
    description: 'Clear the view cache',
)]
class ViewClearCache extends Command
{
    #[Inject('view_cache')]
    private string $viewCache;

    public function __invoke(
        OutputInterface $output,
    ): int {
        if (strtolower($this->viewCache) === 'false') {
            $output->writeln('<fg=yellow>View caching is currently disabled, nothing to clear</>');

            return self::SUCCESS;
        }

        $output->write('Clearing view cache ... ');
        $this->clearViewCache();
        $output->writeln('<fg=green>DONE</>');

        $output->writeln(sprintf('<fg=green>%s cleared successfully</>', $this->viewCache));

        return self::SUCCESS;
    }

    private function clearViewCache(): void
    {
        $directoryIterator = new RecursiveDirectoryIterator($this->viewCache, FilesystemIterator::SKIP_DOTS);
        $iteratorIterator = new RecursiveIteratorIterator($directoryIterator, RecursiveIteratorIterator::CHILD_FIRST);

        /** @var SplFileInfo $file */
        foreach ($iteratorIterator as $file) {
            $file->isDir() ? rmdir($file->getRealPath()) : unlink($file->getRealPath());
        }
    }
}
