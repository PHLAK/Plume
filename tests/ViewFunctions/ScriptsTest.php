<?php

declare(strict_types=1);

namespace Tests\ViewFunctions;

use App\ViewFunctions\Scripts;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversClass(Scripts::class)]
class ScriptsTest extends TestCase
{
    #[Test]
    public function it_can_return_the_scripts_file_contents(): void
    {
        $this->container->set('scripts_file', $this->filePath('customizations/scripts'));
        $scripts = new Scripts($this->config);

        $this->assertEquals('<!-- Test file; please ignore -->', $scripts());
    }

    #[Test]
    public function it_does_not_return_anything_when_the_scripts_file_does_not_exist(): void
    {
        $this->container->set('scripts_file', 'NONEXISTENT_FILE');
        $scripts = new Scripts($this->config);

        $this->assertEquals('', $scripts());
    }
}
