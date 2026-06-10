<?php

declare(strict_types=1);

namespace App\Commands;

use DI\Attribute\Inject;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(
    name: 'purge:container-cache',
    description: 'Purge the container cache',
)]
class PurgeContainerCache extends BaseCommand
{
    #[Inject('cache_path')]
    private string $cachePath;

    #[Inject('base_path')]
    private string $basePath;

    public function __invoke(): int
    {
        $compiledContainer = sprintf('%s/CompiledContainer.php', $this->cachePath);

        $this->start('Purging the container cache');

        if (file_exists($compiledContainer)) {
            $this->process('Deleting the container cache', fn (): bool => unlink($compiledContainer));
        }

        $this->success(sprintf('Purged <fg=green>%s</> successfully', $this->relativePath($compiledContainer)));

        return self::SUCCESS;
    }

    public function relativePath(string $toPath): string
    {
        return ltrim(str_replace($this->basePath, '', $toPath), DIRECTORY_SEPARATOR);
    }
}
