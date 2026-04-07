<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Tempest\Support\Html\HtmlString;

use function TempestPico\Support\toHtml;

/**
 * @internal
 */
class FunctionsTest extends TestCase
{
    #[Test]
    public function toHtml(): void
    {
        $this->assertSame(
            'Hello, World!',
            toHtml('Hello, World!')->toString(),
        );

        $this->assertSame(
            '&lt;strong&gt;Bold&lt;/strong&gt;',
            toHtml('<strong>Bold</strong>')->toString(),
        );

        $this->assertSame(
            '67.1',
            toHtml(67.1)->toString(),
        );

        $this->assertSame(
            '<hr />',
            toHtml(new HtmlString('<hr />'))->toString(),
        );
    }
}
