<?php

declare(strict_types=1);

namespace App\Commands;

use App\Posts;
use DI\Attribute\Inject;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Contracts\Cache\CacheInterface;

#[AsCommand(
    name: 'publish:post',
    description: 'Publish a post by slug',
    help: 'Coming soon...',
)]
class PublishPost extends Command
{
    #[Inject(CacheInterface::class)]
    private CacheInterface $cache;

    #[Inject(Posts::class)]
    private Posts $posts;

    #[Inject(QuestionHelper::class)]
    private QuestionHelper $question;

    public function __invoke(
        InputInterface $input,
        OutputInterface $output,
        #[Argument('The post slug')] string $slug,
    ): int {
        if ($this->cache instanceof ArrayAdapter) {
            $output->writeln("<comment>This command has no affect when using the 'array' cache driver.</comment>");

            return self::INVALID;
        }

        $this->cache->delete(sprintf('post|%s', $slug));

        $post = $this->posts->get($slug);

        $output->writeln(sprintf('<bg=green;fg=black;options=bold> SUCCESS </> "%s" was published', $post->title));

        if (! $this->question->ask($input, $output, new ConfirmationQuestion('Update posts page? [y/N] ', false))) {
            return Command::SUCCESS;
        }

        $this->cache->delete('all-posts');
        $posts = $this->posts->all();

        $output->writeln('<bg=green;fg=black;options=bold> SUCCESS </> Posts page was updated');

        return Command::SUCCESS;
    }
}
