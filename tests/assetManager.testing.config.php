<?php

declare(strict_types=1);

use AKl\Tempest_HtmlView\AssetManagerConfig;

return new AssetManagerConfig(
    srcRoot: __DIR__ . '/',
    includeRoot: 'assets/inc',
    targetRoot: '.testoutput',
    targetPath: 'assets',
    linkPrefix: '/my-repro',
);
