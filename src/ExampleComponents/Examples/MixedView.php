<?php

declare(strict_types=1);

namespace TempestPico\Components\Examples;

use TempestPico\ExampleComponents\MixedView;

use function AKl\Tempest_HtmlView\content;

return content(new MixedView('This is a Tempest/View'));
