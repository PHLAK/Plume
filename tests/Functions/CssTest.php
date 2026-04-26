<?php

declare(strict_types=1);

namespace Tests\Functions;

use App\Functions\Css;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Twig\Markup;

#[CoversClass(Css::class)]
class CssTest extends TestCase
{
    #[Test]
    public function it_returns_a_script_tag_for_a_script(): void
    {
        /** @var Markup $ */
        $linkTag = $this->container->call(Css::class, ['stylesheet' => 'alpha']);

        $this->assertEquals('<link rel="stylesheet" href="/theme/css/alpha.css">', (string) $linkTag);
    }
}
