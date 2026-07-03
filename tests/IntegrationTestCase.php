<?php

declare(strict_types=1);

namespace Tests;

use Tempest\Framework\Testing\IntegrationTest;

abstract class IntegrationTestCase extends IntegrationTest
{
    protected string $root = __DIR__ . '/../';

    /**
     * @param string $url to test
     * @param string $content something the Page must have but change rawly like the Headline
     **/
    protected function pageHasContent(string $url, string $content): void
    {
        $this->http
            ->get($url)
            ->assertOk()
            ->assertSee($content);
    }
}
