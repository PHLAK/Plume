<?php

declare(strict_types=1);

namespace Tests\ViewFunctions;

use App\ViewFunctions\Vite;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Twig\Markup;

#[CoversClass(Vite::class)]
class ViteTest extends TestCase
{
    #[Test]
    public function it_can_get_tags_for_a_list_of_assets_when_in_dev_mode(): void
    {
        $this->container->set('manifest_path', $this->filePath('public/build/non-existent.json'));

        $tags = $this->container->call(Vite::class, ['assets' => ['resources/js/app.js', 'resources/css/app.css']]);

        $this->assertInstanceOf(Markup::class, $tags);
        $this->assertSame(<<<HTML
        <script type="module" src="http://localhost:5173/@vite/client"></script>
        <script type="module" src="http://localhost:5173/resources/js/app.js"></script>
        <link rel="stylesheet" href="http://localhost:5173/resources/css/app.css">
        HTML, (string) $tags);
    }

    #[Test]
    public function it_can_get_tags_for_a_list_of_assets_when_in_build_mode(): void
    {
        $this->container->set('manifest_path', $this->filePath('public/build/manifest.json'));

        $tags = $this->container->call(Vite::class, ['assets' => ['resources/js/app.js', 'resources/css/app.css']]);

        $this->assertInstanceOf(Markup::class, $tags);
        $this->assertSame(<<<HTML
        <script type="module" src="/build/assets/app-l0sNRNKZ.js"></script>
        <link rel="stylesheet" href="/build/assets/app-DcS355RW.css">
        HTML, (string) $tags);
    }
}
