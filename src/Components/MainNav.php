<?php

declare(strict_types=1);

namespace TempestPico\Components;

use AKl\Tempest_HtmlView\HtmlContent;
use AKl\Tempest_HtmlView\HtmlView;
use Dom\HTMLDocument;
use Tempest\Support\Html\HtmlString;
use Tempest\View\View;

use function AKl\Tempest_HtmlView\Html;

#[Doc('⚠️ You need to set the breakpoint in the css file!')]
final class MainNav extends HtmlView
{
    /**
     * @param array<string, string|\Stringable|HtmlString|HTMLDocument|HtmlContent|View> $mainLinks
     * @param array<string, string|\Stringable|HtmlString|HTMLDocument|HtmlContent|View> $burgerLinks
     **/
    public function __construct(
        private(set) array $mainLinks,
        private(set) array $burgerLinks,
        private(set) string $linkPrefix = '/',
    ) {
        parent::__construct();
    }

    public function template(): HtmlContent
    {
        return Html(
            'nav',
            [
                new LinkList($this->mainLinks, linkPrefix: $this->linkPrefix),
                new BurgerMenu($this->burgerLinks, Id: 'menu-popover', linkPrefix: $this->linkPrefix),
            ],
            [
                'class' => 'container',
                'aria-label' => 'Main Navigation',
                // 'style' => cssVar(nav_breakpoint: '20ch',), // sorry, you can't use css vars in queries
            ],
        );
    }
}
