<?php

declare(strict_types=1);

namespace AKl\Tempest_HtmlView;

use Tempest\Container\Singleton;

use function AKl\Tempest_HtmlView\getRealPath;
use function Tempest\Router\uri;
use function Tempest\Support\Arr\each;
use function Tempest\Support\Arr\has_key;
use function Tempest\Support\Arr\is_empty;
use function Tempest\Support\Arr\map_with_keys;
use function Tempest\Support\Filesystem\copy_file;
use function Tempest\Support\Json\encode;
use function Tempest\Support\path;
use function Tempest\Support\str;

#[Singleton]
final class AssetManager
{
    /** @var array<non-empty-string, array{type: 'Script' | 'Module' |'CSS' | 'File', src: non-empty-string, target: non-empty-string}> $versioned */
    private(set) array $versioned = [];

    /** @var array<non-empty-string, array{src: non-empty-string, download: bool|pure-Closure():bool}> $scripts */
    private(set) array $scripts = [];

    /** @var array<non-empty-string, array{src: non-empty-string, download: bool|pure-Closure():bool}> $modules */
    private(set) array $modules = [];

    /** @var array<non-empty-string, non-empty-string[]> $css  ['layer'] = name[] */
    private(set) array $css = [];

    // TODO: implement feature: static:generate command to dispatches StaticPageGenerationStart
    private readonly bool $isDev;

    public function __construct(
        private(set) AssetManagerConfig $config,
    ) {
        $this->isDev = false; // in_array(env('ENVIRONMENT'), ['local', 'testing'], true); // TODO: improve

        // $this->scanDirectory($this->config->includeRoot, '');
    }

    /*
     * public function staticAsset(string $path): string
     * {
     * return $this->asset($path, $this->config->includeRoot);
     * }
     */

    private function targetPath(string $name): string
    {
        return $this->isDev
            ? uri(AssetRoute::class, file: urlencode($name))
            : $this->versioned[$name]['target'];
    }

    private function resolveSource(string $name, bool $isRealPath): string
    {
        return $isRealPath
            ? $name
            : getRealPath($this->config->srcRoot . '/' . $name);
    }

    private function ensureSourceExists(string $src): void
    {
        if (! is_file($src)) {
            throw new \RuntimeException("Asset source not found: {$src}");
        }
    }

    private function buildAssetHash(string $src): string
    {
        // @phpstan-ignore argument.type
        return str(base64_encode(hash_file('xxh3', $src, true)))
            ->replace(['+', '/'], ['-', '_'])
            ->stripEnd('=')
            ->toString();
    }

    private function buildVersionedName(string $filename, string $hash): string
    {
        return path($filename)
            ->replaceLast('.', "-{$hash}.")
            ->toString();
    }

    private function detectAssetType(string $extension): string
    {
        return match ($extension) {
            'js' => 'Script',
            'mjs' => 'Module',
            'css' => 'CSS',
            default => 'File',
        };
    }

    /**
     * Get versioned asset path.
     *
     * @param non-empty-string $name
     */
    private function normalizeAssetName(string $name): string
    {
        return path($name)->basename()->toString();
    }

    public function use(string $name, bool $isRealPath = false): string
    {
        $assetName = $this->normalizeAssetName($name);

        if (has_key($this->versioned, $assetName)) {
            return $this->targetPath($assetName);
        }

        $src = $this->resolveSource($name, $isRealPath);
        $this->ensureSourceExists($src);

        $versioned = $this->buildVersionedName($assetName, $this->buildAssetHash($src));
        $target = $this->config->linkPrefix . $this->config->targetPath . '/' . $versioned;

        $this->versioned[$assetName] = [
            'src' => $src,
            'target' => $target,
            'type' => $this->detectAssetType(path($assetName)->extension()->lower()->toString()),
        ];

        return $target;
    }

    /**
     * Generate importMap with file hashes.
     */
    public function generateImportMap(): ?string
    {
        if (is_empty($this->modules)) {
            return null;
        }
        // ensure_directory_exists($this->publicDir);
        return encode(
            ['imports' => map_with_keys(
                $this->modules,
                static fn ($module, $name) => yield $name => $module['src'],
            )],
        );
    }

    /*
     * TODO: rename to FromCDN?
     *
     * TODO: download
     *
     * @param non-empty-string $src
     */
    public function requireScript(string $src, bool $install = false): void
    {
        // @mago-expect analysis:invalid-property-assignment-value
        $this->scripts[$src] = [
            'src' => $src,
            'download' => $install,
        ];
    }

    /*
     *
     * TODO: https://unpkg.com/:package@:version/:file
     *
     * TODO: download
     *
     * @param non-empty-string $name
     * @param non-empty-string $src
     *
     */
    public function requireModule(string $name, string $src, bool $install = false): void
    {
        // @mago-expect analysis:invalid-property-assignment-value
        $this->modules[$name] = [
            'src' => $src,
            'download' => $install,
        ];
    }

    /**
     * Layered CSS
     *
     * @param non-empty-string $src
     * @param non-empty-string $layer
     */
    public function css(string $src, string $layer = 'components', bool $isRealPath = false): void
    {
        $this->use($src, isRealPath: $isRealPath);
        $this->css[$layer][] = \basename($src); // ! make sure this matches the name used in versioned
    }

    /*
     * @param non-empty-string $fullPath,
     * @param non-empty-string $prefix
     * /
     * private function addVersioned(string $fullPath, string $prefix, $type): void
     * {
     * $ext = after_last($fullPath, '.');
     * if ($this->isDev) {
     * $relativePath = to_relative_path($this->config->srcRoot, $fullPath);
     * $this->addToMap(
     * $ext,
     * $fullPath,
     * /** (non-empty-string)
     * @phpstan-ignore argument.type
     * /
     * $this->config->linkPrefix . uri(AssetRoute::class, path: urlencode($relativePath)),
     * );
     * return;
     * }
     *
     * // @phpstan-ignore argument.type
     * $hash = substr(md5_file($fullPath), 0, 10);
     * $name = basename($fullPath, '.' . $ext);
     * $versioned = $name . '.' . $hash . '.' . $ext;
     *
     * $this->addToMap($ext, $fullPath, $prefix ? $prefix . '/' . $versioned : $versioned);
     * }
     *
     * private function scanDirectory(string $dir, string $prefix): void
     * {
     * $files = list_directory($dir);
     * // print_r($files);
     * foreach ($files as $file) {
     * $fullPath = $file;
     * $logicalPath = $prefix ? $prefix . '/' . $file : $file;
     *
     * if (is_dir($fullPath)) {
     * $this->scanDirectory($fullPath, $logicalPath);
     * continue;
     * }
     *
     * $file = basename($file);
     * $this->addVersioned($fullPath, $logicalPath);
     * }
     * }
     *
     * /*
     * @param non-empty-string $name,
     * @param non-empty-string $target
     * /
     * private function addToMap(string $type, string $name, string $target): void
     * {
     * switch ($type) {
     * case 'js':
     * $this->importMap['JS'][$name] = $target;
     * break;
     * case 'css':
     * $this->importMap['CSS'][$name] = $target;
     * break;
     * default:
     * $this->importMap['Files'][$name] = $target;
     * }
     * }
     */
    public function copyAssets(): void
    {
        // FIXME: no need to execute it on every single request
        each(
            $this->versioned,
            function (array $asset, $name): void {
                // @phpstan-ignore offsetAccess.notFound, offsetAccess.notFound
                copy_file($asset['src'], $this->config->targetRoot . '/' . $asset['target']);
            },
        );

        // $this->recursiveCopy($this->config->includeRoot, $this->config->targetPath);
    }

    /*
     * private function recursiveCopy(string $src, string $dst): void
     * {
     * ensure_directory_exists($dst);
     *
     * $files = list_directory($src);
     *
     * foreach ($files as $file) {
     * $srcFile = $src . '/' . $file;
     * $dstFile = $dst . '/' . $file;
     *
     * if (is_dir($srcFile)) {
     * $this->recursiveCopy($srcFile, $dstFile);
     * } else {
     * copy_file($srcFile, $dstFile);
     * }
     * }
     * }
     */
}
