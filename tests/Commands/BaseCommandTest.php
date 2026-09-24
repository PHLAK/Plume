<?php

declare(strict_types=1);

namespace Tests\Commands;

use App\Commands\BaseCommand;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\LogicException;
use Symfony\Component\Console\Tester\CommandTester;
use Tests\TestCase;

#[CoversClass(BaseCommand::class)]
class BaseCommandTest extends TestCase
{
    /** @throws LogicException */
    #[Test]
    public function it_outputs_messages_for_each_of_its_helper_methods(): void
    {
        $command = new class('test:output') extends BaseCommand {
            public function runHelpers(): int
            {
                $this->start('Doing the thing');
                $this->process('Doing work', fn (): string => 'work result');
                $this->success('All done');
                $this->newLine();
                $this->warning('Careful now');
                $this->error('Something broke');

                return Command::SUCCESS;
            }
        };

        $command->setCode($command->runHelpers(...));

        (new Application)->addCommand($command);

        $tester = new CommandTester($command);
        $statusCode = $tester->execute([]);

        $this->assertSame(Command::SUCCESS, $statusCode);
        $this->assertSame(<<<TEXT
         ● Doing the thing
         │ Doing work ... DONE
         ● All done

         ● Careful now
         ■ Something broke
        TEXT . "\n", $tester->getDisplay());
    }
}
