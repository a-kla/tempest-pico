<?php

declare(strict_types=1);

namespace TempestPico\Components;

use Tempest\View\IsView;
use Tempest\View\View;

#[Doc('Just plan HTML in a Tempest View for testing')]
final class TestView implements View
{
    use IsView;

    public function __construct()
    {
        $this->path = __DIR__ . '/TestView.view.php';
    }
}
