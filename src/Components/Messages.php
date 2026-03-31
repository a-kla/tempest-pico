<?php

declare(strict_types=1);

namespace TempestPico\Components;

use Tempest\View\View;

#[Doc('Renders the given messages card-like. Type can be error, warning or info.', ['Custom'])]
final class Messages implements View
{
    use IsView;

    /** @param array{type: 'error' | 'warning' | 'info' , md: string}[] $msgs */
    public function __construct(
        public array $msgs,
    ) {
        $this->setPaths();
    }
}
