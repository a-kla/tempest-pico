<?php

declare(strict_types=1);

namespace TempestPico\Components;

use AKl\Tempest_HtmlView\HtmlContent;
use AKl\Tempest_HtmlView\HtmlView;
use Dom\HTMLDocument;
use Tempest\Support\Path\Path;

use function AKl\Tempest_HtmlView\content;
use function AKl\Tempest_HtmlView\Dom;

#[Doc('Use Html using PHP\'s `Dom\HTMLDocument`', ['Helper'])]
final class PhpDom extends HtmlView
{
    private(set) HTMLDocument $dom;

    public function __construct(
        string|Path $HtmlOrFile,
        // do not throw an error if the HTML is not well formed, try to fix it instead (default: true)
        private(set) bool $fixHtml = true,
    ) {
        parent::__construct();

        $this->dom = Dom($HtmlOrFile, $fixHtml);
    }

    public function template(): HtmlContent
    {
        return content($this->dom);
    }
}
