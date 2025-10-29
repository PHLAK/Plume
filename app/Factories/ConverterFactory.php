<?php

declare(strict_types=1);

namespace App\Factories;

use DI\Attribute\Inject;
use League\CommonMark\ConverterInterface;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\FrontMatter\FrontMatterExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\MarkdownConverter;
use PomoDocs\CommonMark\Alert\AlertExtension;
use Spatie\CommonMarkShikiHighlighter\HighlightCodeExtension;

class ConverterFactory
{
    #[Inject('commonmark_config')]
    private array $commonmarkConfig;

    #[Inject('shiki_theme_id')]
    private string $shikiThemeId;

    public function __invoke(): ConverterInterface
    {
        $environment = new Environment($this->commonmarkConfig);

        $environment->addExtension(new CommonMarkCoreExtension);
        $environment->addExtension(new GithubFlavoredMarkdownExtension);
        $environment->addExtension(new FrontMatterExtension);
        $environment->addExtension(new HighlightCodeExtension($this->shikiThemeId));
        $environment->addExtension(new AlertExtension);

        return new MarkdownConverter($environment);
    }
}
