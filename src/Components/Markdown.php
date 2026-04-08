<?php

declare(strict_types=1);

namespace TempestPico\Components;

use League\CommonMark\MarkdownConverter;
use Tempest\Support\Html\HtmlString;
use TempestPico\Support\Html\HtmlViewTree;

use function Tempest\get;
use function TempestPico\Support\Html;

#[Doc('Renders the given GitHub flavored Markdown as HTML. Uses [league/commonmark](https://commonmark.thephpleague.com/2.x/) with close to all Extensions.', ['Helper'])]
final class Markdown implements Component
{
    use IsComponent;

    public function __construct(
        public string $md,
    ) {
        $this->setPaths();
    }

    public function getViewTree(): HtmlViewTree
    {
        $markdown = get(MarkdownConverter::class);
        /* Using directly HtmlString is dangerous,
         * but since the HTML in the markdown is escaped by league/commonmark, it should be safe to use here. */
        $content = new HtmlString($markdown->convert($this->md)->getContent());

        return Html(null, [$content]);
    }
}
