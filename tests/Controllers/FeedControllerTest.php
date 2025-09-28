<?php

declare(strict_types=1);

namespace Tests\Controllers;

use App\Controllers\FeedController;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Tests\TestCase;

#[CoversClass(FeedController::class)]
class FeedControllerTest extends TestCase
{
    #[Test]
    public function it_returns_a_successful_response(): void
    {
        $this->container->set('site_title', 'Test site; please ignore');
        $this->container->set('meta_description', 'This is a test site description.');

        $controller = $this->container->get(FeedController::class);

        $response = $controller($this->createMock(Request::class), new Response);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(200, $response->getStatusCode());

        $this->assertEquals(<<<XML
        <?xml version="1.0" encoding="UTF-8"?>
        <rss version="2.0">
            <channel>
                <title>Test site; please ignore</title>
                <description>This is a test site description.</description>
                <link>http://localhost/</link>
                <generator>PlumePHP</generator>

                <item>
                    <title>Test Post; Please Ignore</title>
                    <link>http://localhost/test-post</link>
                    <pubDate>1986-05-20 12:34:56</pubDate>
                    <author>Arthur Dent</author>
                    <guid isPermaLink="false">test-post</guid>
                    <description>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec ullamcorper posuere quam ac varius. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Fusce maximus enim a elementum blandit.</description>
                </item>
            </channel>
        </rss>
        XML, $response->getBody());
    }
}
