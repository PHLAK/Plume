<?php

declare(strict_types=1);

namespace App\Commands;

use DI\Attribute\Inject;
use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(
    name: 'purge:view-cache',
    description: 'Purge the view cache',
)]
class PurgeViewCache extends BaseCommand
{
    #[Inject('view_cache')]
    private string $viewCache;

    #[Inject('base_path')]
    private string $basePath;

    public function __invoke(): int
    {
        if (strtolower($this->viewCache) === 'false') {
            $this->warning('View caching is currently disabled, nothing to clear');

            return self::SUCCESS;
        }

        $this->start('Purging the view cache');
        $this->process('Deleting view cache files', fn () => $this->clearViewCache());
        $this->success(sprintf('Purged <fg=green>%s</> successfully', $this->cachePath()));

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

    private function cachePath(): string
    {
        return ltrim(str_replace($this->basePath, '', $this->viewCache), DIRECTORY_SEPARATOR);
    }
}
