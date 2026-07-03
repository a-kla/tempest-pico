<?php

declare(strict_types=1);

namespace AKl\Tempest_HtmlView;

use function AKl\Tempest_HtmlView\getRealPath;
use function Tempest\root_path;
use function Tempest\Support\Path\to_relative_path;

final class AssetManagerConfig
{
        private(set) string $srcRoot;
    /* If srcRoot = src & linkPrefix = /my-repro/ … */
    private(set) string $includeRoot; // = assets/versioned => src/assets/versioned/
    private(set) string $targetRoot; // = ../public => public/
    private(set) string $targetPath; // = assets => public/assets/

    /**
     * @example
     * ```
     * // src/config/assetManager.config.php
     * new AssetManagerConfig(
     *     srcRoot: root_path('src'),
     *     targetRoot: root_path('public'),
     *     targetPath: 'assets',
     *     linkPrefix: Tempest\env('DEPLOY_PATH', '/'), // in .env `DEPLOY_PATH=/my-repro/`
     *     includeRoot: 'assets/versioned',
     * );
     * ```
     * `<Root>/src/Components/example.js` gets the name `example.js`.
     * And copied to `<Root>/public/assets/example.<version>.js`
     * but links point to `/my-repro/assets/example.<version>.js` to work on your GitHub Pages
     *
     * TODO: auto copy files in `includeRoot`
     **/
    // @mago-expect lint:excessive-parameter-list
    public function __construct(
        ?string $srcRoot = null, // relativ to root_path()
        string $targetRoot = '../public', // relativ to srcRoot
        string $targetPath = 'build/assets', // relativ to targetRoot
        string $includeRoot = 'assets/versioned', // relativ to srcRoot
        private(set) string $linkPrefix = '/',
    ) {
        $this->srcRoot = $srcRoot !== null ? getRealPath($srcRoot) : root_path('src');
        $this->includeRoot = getRealPath($this->srcRoot . '/' . $includeRoot);
        $this->targetRoot = getRealPath($this->srcRoot . '/' . $targetRoot);
        $this->targetPath = to_relative_path($this->targetRoot, getRealPath($this->targetRoot . '/' . $targetPath));
    }
}
