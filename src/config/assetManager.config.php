<?php

declare(strict_types=1);

use AKl\Tempest_HtmlView\AssetManagerConfig;

use function Tempest\env;

return new AssetManagerConfig(
    // @mago-expect analysis:mixed-argument
    linkPrefix: env('DEPLOY_PATH', '/'),
);
