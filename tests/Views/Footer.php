<?php

declare(strict_types=1);

namespace Tests\Views;

use Tempest\Support\Html\HtmlString;
use TempestPico\Components\Component;
use TempestPico\Components\IsComponent;
use TempestPico\Support\Html\HtmlViewTree;

use function TempestPico\Support\Html\Html;

/** A Component that makes use of HtmlViewTree */
final class Footer implements Component
{
    use IsComponent;

    public function __construct(
        public ?string $content,
    ) {
        $this->setPaths();
    }

    public function getViewTree(): HtmlViewTree
    {
        return Html('footer', [$this->content ?? '¢ …']);
    }

    public function toHtml(): HtmlString
    {
        return $this->getViewTree()->render();
    }
}
