<?php

declare(strict_types=1);

namespace Tests\Controllers\Themes;

use App\Controllers\Themes\CssController;
use Fig\Http\Message\StatusCodeInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Response;
use Slim\Views\Twig;
use Tests\TestCase;

#[CoversClass(CssController::class)]
class CssControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->container->set('css_path', $this->filePath('themes/test/css'));
    }

    #[Test]
    public function it_returns_the_stylesheet_for_a_slug(): void
    {
        $response = $this->container->call(CssController::class, [
            'response' => new Response,
            'stylesheet' => 'alpha.css',
        ]);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertSame(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
        $this->assertSame("html { background-color: #F09; }\n", (string) $response->getBody());
    }

    #[Test]
    public function it_returns_an_error_for_a_non_existent_stylesheet(): void
    {
        $testResponse = (new Response)->withStatus(StatusCodeInterface::STATUS_NOT_FOUND);

        $twig = $this->mock(Twig::class);
        $twig->expects($this->once())->method('render')->with($testResponse, 'error.twig', [
            'message' => 'File not found',
        ])->willReturn($testResponse);

        $response = $this->container->call(CssController::class, [
            'response' => $testResponse,
            'stylesheet' => 'non-existent.css',
        ]);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertSame(StatusCodeInterface::STATUS_NOT_FOUND, $response->getStatusCode());
    }
}
