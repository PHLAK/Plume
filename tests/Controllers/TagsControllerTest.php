<?php

declare(strict_types=1);

namespace Tests\Controllers;

use App\Controllers\TagsController;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversClass(TagsController::class)]
class TagsControllerTest extends TestCase
{
    #[Test]
    public function it_can_do_something(): void
    {
        // ...

        $this->markTestIncomplete();
    }
}
