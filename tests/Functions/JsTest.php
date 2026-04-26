<?php

declare(strict_types=1);

namespace Tests\Functions;

use App\Functions\Js;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Twig\Markup;

#[CoversClass(Js::class)]
class JsTest extends TestCase
{
    #[Test]
    public function it_returns_a_script_tag_for_a_script(): void
    {
        /** @var Markup $scriptTag */
        $scriptTag = $this->container->call(Js::class, ['script' => 'alpha']);

        $this->assertEquals('<script src="/theme/js/alpha.js" type="module"></script>', (string) $scriptTag);
    }

    #[Test]
    public function it_returns_a_script_tag_with_modifiers(): void
    {
        $scriptTag = $this->container->call(Js::class, ['script' => 'alpha', 'modifiers' => ['type' => 'test', 'async']]);

        $this->assertEquals('<script src="/theme/js/alpha.js" type="test" async></script>', (string) $scriptTag);
    }
}
