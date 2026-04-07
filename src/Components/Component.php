<?php

declare(strict_types=1);

namespace TempestPico\Components;

use Tempest\Support\Html\HtmlString;
use TempestPico\Support\Html\HTMLBuilder;

interface Component extends \Tempest\View\View
{
    public function __invoke(): HtmlString;

    public function toHtml(): HtmlString;

    public function getViewTree(): HTMLBuilder;
}
