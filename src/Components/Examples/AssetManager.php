<?php

declare(strict_types=1);

namespace TempestPico\Components\Examples;

use AKl\Tempest_HtmlView\AssetManager;

use function AKl\Tempest_HtmlView\Html;
use function Tempest\get;

$assets = get(AssetManager::class);
// Typewriter
$assets->requireScript(
    'https://unpkg.com/typewriter-effect@latest/dist/core.js',
);
$assets->use('Components/Examples/Typewriter.js');

return Html(element: 'div', attributes: ['id' => 'Typewriter']);
