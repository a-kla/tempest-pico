<?php

declare(strict_types=1);

namespace TempestPico\Components;

use Tempest\View\View;

final class Stack implements View
{
    use IsView;

    /**
     * @param View[] $components
     **/
    public function __construct(
        public array $components = [],
        ) {
        $this->setPaths();
    }
}
