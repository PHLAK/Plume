<?php

declare(strict_types=1);

namespace App\Factories;

use DI\Attribute\Inject;
use League\CommonMark\ConverterInterface;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\DescriptionList\DescriptionListExtension;
use League\CommonMark\Extension\Footnote\FootnoteExtension;
use League\CommonMark\Extension\FrontMatter\FrontMatterExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\Extension\HeadingPermalink\HeadingPermalinkExtension;
use League\CommonMark\Extension\TableOfContents\TableOfContentsExtension;
use League\CommonMark\MarkdownConverter;
use PHLAK\CommonMarkExtensions\RewriteLocalImageURLs;
use PomoDocs\CommonMark\Alert\AlertExtension;
use Spatie\CommonMarkShikiHighlighter\HighlightCodeExtension;

class ConverterFactory
{
    #[Inject('commonmark_config')]
    private array $commonmarkConfig;

    #[Inject('shiki_theme_id')]
    private string $shikiThemeId;

    #[Inject('base_url')]
    private string|null $baseUrl;

    public function __invoke(): ConverterInterface
    {
        $environment = new Environment($this->commonmarkConfig);

        $environment->addExtension(new CommonMarkCoreExtension);
        $environment->addExtension(new GithubFlavoredMarkdownExtension);
        $environment->addExtension(new AlertExtension);
        $environment->addExtension(new DescriptionListExtension);
        $environment->addExtension(new FootnoteExtension);
        $environment->addExtension(new FrontMatterExtension);
        $environment->addExtension(new HeadingPermalinkExtension);
        $environment->addExtension(new TableOfContentsExtension);
        $environment->addExtension(new HighlightCodeExtension($this->shikiThemeId));

        if ($this->baseUrl !== null) {
            $environment->addExtension(new RewriteLocalImageURLs($this->baseUrl));
        }

        return new MarkdownConverter($environment);
    }
}
