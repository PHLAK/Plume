<?php

declare(strict_types=1);

namespace Tests\Controllers;

use App\Controllers\PostController;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Tests\TestCase;

#[CoversClass(PostController::class)]
class PostControllerTest extends TestCase
{
    #[Test]
    public function it_returns_a_post_by_slug(): void
    {
        $response = $this->container->call(PostController::class, [
            'slug' => 'test-post-1',
        ]);

        $request = $this->createMock(Request::class);
        // $request->expects($this->never());

        $response = $controller($request, new Response, 'test-post-1');

        dd($response);
    }

    #[Test]
    public function it_returns_an_error_when_fetching_a_non_existent_post(): void
    {
        // ...

        $this->markTestIncomplete();
    }

    #[Test]
    public function it_returns_an_error_when_fetching_a_future_post(): void
    {
        // ...

        $this->markTestIncomplete();
    }

    #[Test]
    public function it_returns_an_error_when_fetching_a_draft_post(): void
    {
        // ...

        $this->markTestIncomplete();
    }
}
