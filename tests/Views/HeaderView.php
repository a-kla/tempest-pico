<?php

declare(strict_types=1);

namespace Tests\Views;

use Tempest\View\IsView;
use Tempest\View\View;

/** A classic Tempest View */
final class HeaderView implements View
{
    use IsView;

    public function __construct(
        public string $homeLinkText,
    ) {
        $this->path = 'tests/Views/header.view.php';
    }
}
