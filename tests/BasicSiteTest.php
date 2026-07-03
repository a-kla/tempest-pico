<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TempestPico\Page\Documentation;
use TempestPico\Page\Hello;

use function Tempest\Router\uri;

/**
 * @internal
 */
class BasicSiteTest extends IntegrationTestCase
{
    private false|string $originalEnvironment = false;

    protected function setUp(): void
    {
        $this->originalEnvironment = getenv('ENVIRONMENT');
        putenv('ENVIRONMENT=local');

        parent::setUp();
    }

    /*
     * TODO:
     * Test code or tested code did not remove its own error handlers
     * Test code or tested code did not remove its own exception handlers
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        if ($this->originalEnvironment === false) {
            putenv('ENVIRONMENT');
        } else {
            putenv('ENVIRONMENT=' . $this->originalEnvironment);
        }
    }

    #[Test]
    #[DataProvider('pageProvider')]
    /**
     * @param class-string|array{class-string, string} $route
     */
    public function pages_contain_content(string|array $route, string $content): void
    {
        // @mago-expect analysis:possibly-invalid-argument
        $uri = uri($route);

        // Debug-Label
        echo "Testing page: {$uri}\n";

        $this->http
            ->get($uri)
            ->assertOk()
            ->assertSee($content);
    }

    public static function pageProvider(): array
    {
        return [
            [Hello::class, 'World'],

            // TODO: search only main content
            [Documentation::class, 'Overview'],
            [[Documentation::class, 'components'], 'CodeBlock'],
            [[Documentation::class, 'examples'], 'HelloWorld'],
            [[Documentation::class, 'readme'], 'Deploy'],
            [[Documentation::class, 'dom'], 'PHP8.4'],
            [[Documentation::class, 'assetManager'], 'versioned'],
        ];
    }
}
