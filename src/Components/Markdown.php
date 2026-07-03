<?php

declare(strict_types=1);

namespace TempestPico\Components;

use AKl\Tempest_HtmlView\HtmlContent;
use AKl\Tempest_HtmlView\HtmlView;
use League\CommonMark\MarkdownConverter;
use Tempest\Support\Html\HtmlString;

use function AKl\Tempest_HtmlView\content;
use function Tempest\get;

#[
    Doc(
        'Renders the given GitHub flavored Markdown + as HTML. Shortcut: `MD()`',
        ['Helper'],
    ),
]
final class Markdown extends HtmlView
{
    public function __construct(
        public string $md,
    ) {
        parent::__construct();
    }

    public function template(): HtmlContent
    {
        $markdown = get(MarkdownConverter::class, 'GFM');
        /* Using directly HtmlString is dangerous,
         * but since the HTML in the markdown is escaped by league/commonmark, it should be safe to use here. */
        $htmlString = new HtmlString(
            $markdown->convert($this->md)->getContent(),
        );

        return content($htmlString);
    }
}
