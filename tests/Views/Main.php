<?php

declare(strict_types=1);

namespace Tests\Views;

use AKl\Tempest_HtmlView\HtmlContent;
use AKl\Tempest_HtmlView\HtmlView;

use function AKl\Tempest_HtmlView\Html;
use function AKl\Tempest_HtmlView\MD;

/** A Component that makes use of HtmlContent */
final class Main extends HtmlView
{
    public function __construct(
        public string $headline,
    ) {
        parent::__construct();
    }

    public function template(): HtmlContent
    {
        $fromMarkdown = MD(
            <<<MD
                ## Main Component

                Be *careful* if you use `new HtmlString()`<b>!</b>

                -<script>alert("Hello XSS!");</script>-
                MD,
        )
            // remove spaces for easier testing…
            ->render()
            ->trim()
            ->replaceRegex("/>\s*</", '><');

        return Html('main', [Html('h1', [$this->headline]), $fromMarkdown]);
    }
}
