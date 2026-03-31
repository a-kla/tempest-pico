<?php

declare(strict_types=1);

namespace TempestPico\Components;

use Tempest\View\View;

#[Doc('Renders the given GitHub flavored Markdown as HTML. Uses [league/commonmark](https://commonmark.thephpleague.com/2.x/) with close to all Extensions.', ['Helper'])]
final class Markdown implements View
{
    use IsView;

    public function __construct(
        public string $md,
    ) {
        $this->setPaths();
    }
}
