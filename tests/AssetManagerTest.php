<?php

declare(strict_types=1);

namespace Tests;

use AKl\Tempest_HtmlView\AssetManager;
use PHPUnit\Framework\Attributes\Test;
use Tempest\Framework\Testing\IntegrationTest;

use function Tempest\get;

// use function TempestPico\Support\toHtml;

/**
 * @internal
 */
class AssetManagerTest extends IntegrationTest
{
    #[Test]
    public function usesRelativeAssetPathInDev(): void
    {
        self::markTestSkipped('TODO: reimplement feature');

        $assets = get(AssetManager::class);
        $this->assertSame(
            '/localAsset?assets%2Flog.js',
            $assets->use('assets/log.js'),
        );
    }

    #[Test]
    public function throwsWhenAssetDoesNotExist(): void
    {
        $assets = get(AssetManager::class);

        $this->expectException(\Exception::class);

        $assets->use('no-asset.js');
    }

    #[Test]
    public function usesVersionedAssetPathInProduction(): void
    {
        $originalEnvironment = getenv('ENVIRONMENT');

        putenv('ENVIRONMENT=production');

        try {
            $assets = get(AssetManager::class);

            // $assets->require('assets/log.js');

            // print_r($assets->importMap);

            $this->assertSame(
                '/my-reproassets/log-N74Uc_NfdyI.js',
                $assets->use('assets/log.js'),
            );

            /*
             * $this->assertSame(
             * '/my-repro/assets/build/test.js',
             * $assets->staticAsset('test.js'),
             * );
             */
        } finally {
            if ($originalEnvironment === false) {
                putenv('ENVIRONMENT');
            } else {
                putenv('ENVIRONMENT=' . $originalEnvironment);
            }
        }
    }

    #[Test]
    public function buildsImportMapForExternalModules(): void
    {
        // putenv('ENVIRONMENT=prod');
        $assets = get(AssetManager::class);

        // $assets->require('assets/log.js');

        // https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4

        $assets->requireModule(
            'typewriter-effect',
            'https://unpkg.com/typewriter-effect@latest/dist/core.js', // TODO: version
        );

        $importMap = $assets->generateImportMap();
        // @mago-expect analysis:possibly-null-argument
        json_decode($importMap, true, flags: JSON_THROW_ON_ERROR);

        $this->assertStringContainsString(
            // @mago-expect analysis:possibly-null-argument
            '"typewriter-effect":"https://unpkg.com/typewriter-effect@latest/dist/core.js"',
            $importMap,
        );

        /*
         * $this->assertSame(
         * '/my-repro/assets/build/test.js',
         * $assets->staticAsset('test.js'),
         * );
         */
    }

    /*
     * #[Test]
     * public function Module(): void
     * {
     * // putenv('ENVIRONMENT=prod');
     * $assets = get(AssetManager::class);
     *
     * // $assets->require('assets/log.js');
     *
     * // https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4
     *
     * $assets->addModule(
     * 'tailwindcss',
     * 'https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4', // TODO: version
     * );
     *
     * $this->assertSame(
     * '/my-repro/assets/test-D53oXMiRyYU=.js',
     * $assets->asset('test.js'),
     * );
     *
     * /*
     * $this->assertSame(
     * '/my-repro/assets/build/test.js',
     * $assets->staticAsset('test.js'),
     * );
     * }
     */
    // TODO: typewriter-effect: https://unpkg.com/typewriter-effect@latest/dist/core.js
}
