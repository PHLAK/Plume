<?php

declare(strict_types=1);

namespace Tests\ViewFunctions;

use App\ViewFunctions\Markdown;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversClass(Markdown::class)]
class MarkdownTest extends TestCase
{
    #[Test]
    public function it_can_parse_markdown_into_html(): void
    {
        $markdown = $this->container->call(Markdown::class, ['markdown' => '**Test** `markdown`, ~~please~~ _ignore_']);

        $this->assertEquals("<p><strong>Test</strong> <code>markdown</code>, <del>please</del> <em>ignore</em></p>\n", $markdown);
    }
}
