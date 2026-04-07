<?php

declare(strict_types=1);

namespace Tests\Views;

use League\CommonMark\CommonMarkConverter;
use Tempest\Support\Html\HtmlString;
use TempestPico\Components\Component;
use TempestPico\Components\IsComponent;
use TempestPico\Support\Html\HTMLBuilder;

use function TempestPico\Support\Html;

/** A Component that makes use of HTMLBuilder */
final class Main implements Component
{
    use IsComponent;

    public function __construct(
        public string $headline,
    ) {
        $this->setPaths();
    }

    public function getViewTree(): HTMLBuilder
    {
        $fromMarkdown = new HtmlString(
            new CommonMarkConverter()
                ->convert("## Hello!\n\nBe *careful* if you use `new HtmlString()` <b>!</b>")
                ->getContent(),
        )
            // remove spaces for easier testing…
            ->trim()
            ->replaceRegex('/>\s*</', '><');

        return Html('main', [Html('h1', [$this->headline]), $fromMarkdown]);
    }

    public function toHtml(): HtmlString
    {
        return $this->getViewTree()->render();
    }
}
