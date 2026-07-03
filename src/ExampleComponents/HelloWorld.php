<?php

declare(strict_types=1);

namespace TempestPico\ExampleComponents;

use AKl\Tempest_HtmlView\HtmlContent;
use AKl\Tempest_HtmlView\HtmlView;
use TempestPico\Components\Doc;

use function AKl\Tempest_HtmlView\content;
use function AKl\Tempest_HtmlView\Html;

#[Doc('A minimal Example/Template', ['Example', 'helper'])]
final class HelloWorld extends HtmlView
{
    public function __construct(
        private readonly string $name = 'World',
    ) {
        parent::__construct();
    }

    public function template(): HtmlContent
    {
        /*
         * Of course in this case using Tempest/Views would be easier,
         * but this is just a minimal example to show how HtmlViews work.
         *
         * Compare ListHtml and ListHtmlView to see the benefits of using HtmlViews.
         */
        return content(
            'Hello ',
            Html('em', $this->name), // The name gets escaped
            '!',
        );

        // XHP: return <x:frag>Hello <em>{$this->name}</em>!</x:frag>;
    }
}
