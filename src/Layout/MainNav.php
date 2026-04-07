<?php

declare(strict_types=1);

namespace TempestPico\Layout;

use Tempest\View\View;

final class MainNav implements View
{
    use IsView;

    /**
     * @param array<string, string> $links
     **/
    public function __construct(
        public array $links,
    ) {
        $this->setPaths();
    }
}
