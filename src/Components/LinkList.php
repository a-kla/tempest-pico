<?php

declare(strict_types=1);

namespace TempestPico\Components;

use AKl\Tempest_HtmlView\HtmlContent;
use AKl\Tempest_HtmlView\HtmlView;
use Dom\HTMLDocument;
use Tempest\Support\Uri\Uri;
use Tempest\View\View;

use function AKl\Tempest_HtmlView\Html;
use function Tempest\Router\is_current_uri;
use function Tempest\Support\Arr\map_iterable;
use function Tempest\Support\Str\strip_start;

final class LinkList extends HtmlView
{
    /**
     * @param array<string, string|\Stringable|HTMLDocument|HtmlContent|View> $links
     **/
    public function __construct(
        private(set) array $links,
        private(set) string $linkPrefix = '/',
    ) {
        parent::__construct();
    }

    public function template(): HtmlContent
    {
        $baseUrl = Uri::from(
            $this->linkPrefix,
        );

        return Html(
            'ul',
            map_iterable(
                $this->links,
                static fn (
                    string|\Stringable|HTMLDocument|HtmlContent|View $text,
                    $url,
                ): HtmlContent => Html(
                    'li',
                    [
                        Html(
                            'a',
                            $text,
                            [
                                'href' => $baseUrl . strip_start($url, '/'),
                                'aria-current' => is_current_uri($baseUrl . strip_start($url, '/')) ? 'page' : null,
                            ],
                        ),
                    ],
                ),
            ),
        );
    }
}
