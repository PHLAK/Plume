<?php

declare(strict_types=1);

namespace Tests\Functions;

use App\Functions\Pages;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversClass(Pages::class)]
class PagesTest extends TestCase
{
    #[Test]
    public function it_returns_a_list_of_pages(): void
    {
        $pages = $this->container->call(Pages::class);

        $this->assertEquals(new Collection([
            'about' => 'About',
            'test' => 'Test', 'last' => 'Last',
        ]), $pages);
    }
}
