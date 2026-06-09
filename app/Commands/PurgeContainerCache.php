<?php

declare(strict_types=1);

namespace App\Commands;

use DI\Attribute\Inject;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'purge:container-cache',
    description: 'Purge the container cache',
)]
class PurgeContainerCache extends Command
{
    #[Inject('cache_path')]
    private string $cachePath;

    public function __invoke(
        OutputInterface $output,
    ): int {
        $compiledContainer = sprintf('%s/CompiledContainer.php', $this->cachePath);

        if (file_exists($compiledContainer)) {
            $output->write('Clearing container cache ... ');
            unlink($compiledContainer);
            $output->writeln('<fg=green>DONE</>');
        }

        $output->writeln(sprintf('<fg=green>%s cleared successfully</>', $compiledContainer));

        return self::SUCCESS;
    }
}
