<?php

declare(strict_types=1);

namespace App\Commands;

use App\Posts;
use DI\Attribute\Inject;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\NamespacedPoolInterface;

#[AsCommand(
    name: 'publish:post',
    description: 'Publish a post by slug',
    help: 'Coming soon...',
)]
class PublishPost extends Command
{
    /** @var CacheInterface&NamespacedPoolInterface */
    #[Inject(CacheInterface::class)]
    private CacheInterface $cache;

    #[Inject(Posts::class)]
    private Posts $posts;

    #[Inject(QuestionHelper::class)]
    private QuestionHelper $question;

    public function __invoke(
        InputInterface $input,
        OutputInterface $output,
        Application $application,
        #[Argument('The post slug')] string $slug,
    ): int {
        if ($this->cache instanceof ArrayAdapter) {
            $output->writeln("<fg=yellow>This command has no affect when using the 'array' cache driver</>");

            return self::FAILURE;
        }

        $output->write('Clearing post cache ... ');
        $this->cache->withSubNamespace('posts')->delete($slug);
        $output->writeln('<fg=green>DONE</>');

        $output->write('Publishing post ... ');
        $post = $this->posts->get($slug);
        $output->writeln('<fg=green>DONE</>');

        $output->writeln(sprintf('<fg=green>"%s" published successfully</>', $post->title));

        if (! $this->question->ask($input, $output, new ConfirmationQuestion('Update posts page? [y/N] ', false))) {
            return Command::SUCCESS;
        }

        return $application->doRun(new StringInput('publish:posts'), $output);
    }
}
