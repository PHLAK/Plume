<?php

declare(strict_types=1);

namespace Tests\Controllers\Themes;

use App\Controllers\Themes\JsController;
use Fig\Http\Message\StatusCodeInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Response;
use Slim\Views\Twig;
use Tests\TestCase;

#[CoversClass(JsController::class)]
class JsControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->container->set('js_path', $this->filePath('themes/test/js'));
    }

    #[Test]
    public function it_returns_the_script_for_a_slug(): void
    {
        $response = $this->container->call(JsController::class, [
            'response' => new Response,
            'script' => 'alpha.js',
        ]);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertSame(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
        $this->assertSame("alert('Test alert; please ignore');\n", (string) $response->getBody());
    }

    #[Test]
    public function it_returns_an_error_for_a_non_existent_script(): void
    {
        $testResponse = (new Response)->withStatus(StatusCodeInterface::STATUS_NOT_FOUND);

        $twig = $this->mock(Twig::class);
        $twig->expects($this->once())->method('render')->with($testResponse, 'error.twig', [
            'message' => 'File not found',
        ])->willReturn($testResponse);

        $response = $this->container->call(JsController::class, [
            'response' => $testResponse,
            'script' => 'non-existent.js',
        ]);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertSame(StatusCodeInterface::STATUS_NOT_FOUND, $response->getStatusCode());
    }
}
