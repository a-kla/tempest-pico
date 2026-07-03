<?php

declare(strict_types=1);

namespace TempestPico\ExampleComponents;

use Tempest\View\IsView;
use Tempest\View\View;
use TempestPico\Components\Doc;

#[
    Doc(
        "Proof: Tempest\View and HtmlView work in combination",
        ['Example'],
        customView: __DIR__ . '/mixedView.view.php',
    ),
]
final class MixedView implements View // not HtmlView
{
    use IsView;

    public function __construct(
        public ?string $title = "This is a title for the Tempest\View!",
    ) {
        $this->path = __DIR__ . '/mixedView.view.php';
    }
}
