<?php

declare(strict_types=1);

namespace App\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BaseCommand extends Command
{
    protected InputInterface $input;
    protected OutputInterface $output;

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $this->input = $input;
        $this->output = $output;
    }

    protected function start(string $message): void
    {
        $this->output->writeln(sprintf(' ● <options=bold>%s</>', $message));
    }

    /**
     * @template TReturn
     *
     * @param callable(): TReturn $action
     *
     * @return TReturn
     */
    protected function process(string $message, callable $action): mixed
    {
        $this->output->write(sprintf(' <fg=black>│ %s ...</> ', $message));
        $return = $action();
        $this->output->writeln('<fg=green>DONE</>');

        return $return;
    }

    protected function success(string $message): void
    {
        $this->output->writeln(sprintf(' <fg=green>●</> %s', $message));
    }

    protected function warning(string $message): void
    {
        $this->output->writeln(sprintf(' <fg=yellow>●</> %s', $message));
    }

    protected function error(string $message): void
    {
        $this->output->writeln(sprintf(' <fg=red>●</> %s', $message));
    }

    protected function newLine(): void
    {
        $this->output->writeln('');
    }
}
