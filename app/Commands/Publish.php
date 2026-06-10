<?php

declare(strict_types=1);

namespace App\Commands;

use DI\Attribute\Inject;
use Symfony\Component\Cache\Adapter\AbstractAdapter;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Attribute\Option;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\Cache\CacheInterface;

#[AsCommand('publish', description: 'Publish all posts and pages and purge caches')]
class Publish extends BaseCommand
{
    /** @var AbstractAdapter $cache */
    #[Inject(CacheInterface::class)]
    private CacheInterface $cache;

    public function __invoke(
        OutputInterface $output,
        Application $application,
        #[Option('Purge view and container caches after publishing')] bool $purgeCaches = false
    ): int {
        if ($this->cache instanceof ArrayAdapter) {
            $output->writeln("<fg=yellow>This command has no affect when using the 'array' cache driver</>");

            return self::FAILURE;
        }

        if ($purgeCaches) {
            $application->doRun(new StringInput('purge:view-cache'), $output);
            $this->newLine();
            $application->doRun(new StringInput('purge:container-cache'), $output);
            $this->newLine();
        }

        $application->doRun(new StringInput('publish:posts'), $output);

        $this->newLine();

        $application->doRun(new StringInput('publish:pages'), $output);

        return Command::SUCCESS;
    }
}
