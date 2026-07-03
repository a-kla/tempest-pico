<?php

declare(strict_types=1);

namespace TempestPico\ExampleComponents;

use Tempest\View\IsView;
use Tempest\View\View;
use TempestPico\Components\Doc;

#[
    Doc(
        "List Example as Tempest\View",
        tags: ['Example'],
        customView: __DIR__ . '/listView.view.php',
    ),
]
final class ListView implements View
{
    use IsView;

    public function __construct(
        /*
         * Mago:
         * This property is declared but never read or written within the class.
         * Consider prefixing the property with an underscore (`$_`) to indicate that it is intentionally unused,
         * or remove it if it is not needed.
         */
        private readonly array $items = [],
        private readonly bool $order = true,
    ) {
        $this->path = __DIR__ . '/listView.view.php';
    }
}
