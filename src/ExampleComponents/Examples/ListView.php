<?php

declare(strict_types=1);

namespace TempestPico\Components\Examples;

use TempestPico\ExampleComponents\ListView;

use function AKl\Tempest_HtmlView\content;

return content(new ListView([1 => 'Foo', 4 => 'Bar', 9 => 'Baz']));
