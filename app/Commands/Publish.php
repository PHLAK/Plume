<?php

declare(strict_types=1);

namespace App\Commands;

use DI\Attribute\Inject;
use Symfony\Component\Cache\Adapter\AbstractAdapter;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\Cache\CacheInterface;

#[AsCommand(
    name: 'publish',
    description: 'Publish all posts, pages, authors and tags',
    help: 'Coming soon...',
)]
class Publish extends Command
{
    /** @var AbstractAdapter $cache */
    #[Inject(CacheInterface::class)]
    private CacheInterface $cache;

    public function __invoke(
        OutputInterface $output,
        Application $application,
    ): int {
        if ($this->cache instanceof ArrayAdapter) {
            $output->writeln("<fg=yellow>This command has no affect when using the 'array' cache driver</>");

            return self::FAILURE;
        }

        $application->doRun(new StringInput('publish:posts'), $output);
        $application->doRun(new StringInput('publish:pages'), $output);
        $application->doRun(new StringInput('publish:authors'), $output);
        $application->doRun(new StringInput('publish:tags'), $output);

        return Command::SUCCESS;
    }
}
