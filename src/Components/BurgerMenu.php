<?php

declare(strict_types=1);

namespace TempestPico\Components;

use AKl\Tempest_HtmlView\HtmlContent;
use AKl\Tempest_HtmlView\HtmlView;
use Dom\HTMLDocument;
use Tempest\Support\Html\HtmlString;
use Tempest\View\View;

use function AKl\Tempest_HtmlView\content;
use function AKl\Tempest_HtmlView\Html;
use function Tempest\Support\Random\uuid;

#[Doc('Creates a Popover for a LinkList', 'Helper')]
final class BurgerMenu extends HtmlView
{
    private(set) string $Id;

    /**
     * @param array<string, string|\Stringable|HtmlString|HTMLDocument|HtmlContent|View> $links
     **/
    public function __construct(
        private(set) array $links,
        ?string $Id = null,
        private(set) string|HtmlContent $buttonContent = '☰',
        private(set) string $linkPrefix = '/',
    ) {
        parent::__construct();
        $this->Id = $Id ?? uuid();

        // get(AssetManager::class)->css('Layout/MainNav.css');
    }

    public function template(): HtmlContent
    {
        return content(
            Html(
                'button',
                $this->buttonContent,
                ['popovertarget' => $this->Id],
            ),
            Html(
                'aside',
                new LinkList($this->links, linkPrefix: $this->linkPrefix),
                [
                    'id' => $this->Id,
                    'popover' => true,
                ],
            ),
        );
    }
}
