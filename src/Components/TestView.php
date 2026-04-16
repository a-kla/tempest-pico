<?php

declare(strict_types=1);

namespace TempestPico\Components;

use Tempest\View\IsView;
use Tempest\View\View;

#[Doc('Proof: old Tempest View and my Components work in combination')]
final class TestView implements View
{
    use IsView;

    public function __construct()
    {
        $this->path = __DIR__ . '/TestView.view.php';
    }
}
