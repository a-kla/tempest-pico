<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\Attributes\Test;

use function Tempest\Support\Arr\each;

/**
 * @internal
 */
class BasicSiteTest extends IntegrationTestCase
{
    #[Test]
    public function pages_contain_content(): void
    {
        each(
            [
                '' => 'Tempest',
                // 'example' => 'Example Page',
                // 'table' => 'Component Example: Table',
                'doc' => 'Overview',
                'doc/components' => 'Components Overview',
            ],
            function (string $content, string $url): void {
                echo "Testing page: /{$url}\n";
                $this->pageHasContent('/' . $url, $content);
            },
        );
    }
}
