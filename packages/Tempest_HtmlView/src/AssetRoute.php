<?php

declare(strict_types=1);

namespace AKl\Tempest_HtmlView;

use Tempest\Http\Response;
use Tempest\Http\Responses\File;
use Tempest\Router\Get;

use function Tempest\Support\Str\contains;
use function AKl\Tempest_HtmlView\getRealPath;

final readonly class AssetRoute
{
    public function __construct(
        private(set) AssetManagerConfig $config,
    ) {}

    #[Get('/localAsset?{file}')]
    public function __invoke(
        string $file,
    ): Response {
        if (contains($file, '../')) {
            // '../.env' maybe leeks DB Password =>
            throw new \Exception('Disallowed', 1);
        }
        return new File(
            getRealPath($this->config->srcRoot . '/' . $file),
        );
    }

    /*
     * #[Get('/localAssetAutoInc?{path}')]
     * public function autoIncluded(
     * string $path,
     * ): Response {
     * return new File(
     * getRealPath($this->config->includeRoot . '/' . $path),
     * );
     * }
     */
}
