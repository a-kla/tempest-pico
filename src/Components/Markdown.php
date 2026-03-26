<?php

declare(strict_types=1);

namespace TempestPico\Components;

use Tempest\View\View;

/**
 * @see https://commonmark.thephpleague.com/2.x/
 */
final class Markdown implements View
{
    use IsView;

    public function __construct(
        public string $md,
    ) {
        $this->setPaths();
    }
}
