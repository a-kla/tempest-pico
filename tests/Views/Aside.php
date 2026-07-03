<?php

declare(strict_types=1);

namespace Tests\Views;

use AKl\Tempest_HtmlView\HtmlContent;
use AKl\Tempest_HtmlView\HtmlView;

use function AKl\Tempest_HtmlView\Html;

/** A Component that makes use of HtmlContent */
final class Aside extends HtmlView
{
    public function __construct(
        protected(set) ?string $headline,
    ) {
        parent::__construct();
    }

    public function template(): HtmlContent
    {
        return Html('aside', [
            Html('h1', $this->headline),
            Html(
                element: 'p',
                content: [
                    'Foo? ',
                    Html('i', 'No. Bar!', ['class' => 'bar']),
                ],
            ),
            Html('hr'),
            '- <= more Text… => -',
        ]);
    }
}
