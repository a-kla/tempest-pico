<?php

declare(strict_types=1);

namespace TempestPico\ExampleComponents;

use AKl\Tempest_HtmlView\HtmlContent;
use AKl\Tempest_HtmlView\HtmlView;
use TempestPico\Components\Doc;

use function AKl\Tempest_HtmlView\Html;
use function Tempest\Support\Arr\map_iterable;

#[Doc('List Example as HtmlView', ['Example'])]
final class ListHtmlView extends HtmlView
{
    public function __construct(
        private readonly array $items = [],
        private readonly bool $order = true,
    ) {
        parent::__construct();
    }

    public function template(): HtmlContent
    {
        return Html(
            $this->order ? 'ol' : 'ul',
            attributes: ['class' => 'list'],
            content: map_iterable(
                $this->items,
                static fn ($item, $id): HtmlContent => Html('li', "Item #{$id} {$item}"),
            ),
        );
    }
}
