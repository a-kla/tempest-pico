<?php

declare(strict_types=1);

namespace TempestPico\Components;

use Tempest\View\View;

#[Doc('Stack multiple components on top of each other to put it in a single View "slot".', ['Helper'])]
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
