<?php

declare(strict_types=1);

namespace TempestPico\ExampleComponents;

use AKl\Tempest_HtmlView\HtmlContent;
use AKl\Tempest_HtmlView\HtmlView;
use Tempest\View\GenericView;
use TempestPico\Components\Doc;

use function AKl\Tempest_HtmlView\content;
use function AKl\Tempest_HtmlView\Html;
use function Tempest\view;

#[Doc("A Tempest\View as HtmlView", ['Example'])]
final class MixedView2 extends HtmlView
{
    public function __construct(
        public ?string $title = 'Title…',
    ) {
        parent::__construct();
    }

    public function template(): HtmlContent
    {
        /*
         * same as Html('h1', $this->title)
         * but this way you can reuse your Tempest/View code
         */
        $adhocView = new GenericView(
            path: // It seem like it do not have to be a path. It works for now…
            <<<'View'
                    <h4>{{ $title }}</h4>
                View,
            data: [
                'title' => $this->title,
            ],
        );

        return content(
            $adhocView,
            Html('h5', 'a HtmlView'),
            new MixedView3(),
            Html('h5', "Tempest\View without Dedicated View Object"),
            view('mixedView.view.php', [
                'title' => 'Now $title is set', // … I thought
            ]),
            Html('h5', "Tempest\View with Dedicated View Object"),
            new MixedView(),
        );
    }
}
