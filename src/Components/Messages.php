<?php

declare(strict_types=1);

namespace TempestPico\Components;

use Tempest\View\View;

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
