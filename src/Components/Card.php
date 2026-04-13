<?php

declare(strict_types=1);

namespace TempestPico\Components;

use Tempest\Support\Html\HtmlString;
use TempestPico\Support\Html\HtmlViewTree;

use function TempestPico\Support\Html\composeStr;
use function TempestPico\Support\Html\Html;

#[Doc('Puts the content inside a `<article>` tag, Pico styles it card-like.', ['Pico'])]
final class Card implements Component
{
    use IsComponent;

    /**
     * @param null|string|array<string, bool|callable(): bool> $class
     * @param null|string|array<string, bool|callable(): bool> $style
     **/
    public function __construct(
        public HtmlString|Component|HtmlViewTree $content,
        public null|HtmlString|Component|HtmlViewTree $header = null,
        public null|HtmlString|Component|HtmlViewTree $footer = null,
        public null|string|array $class = null,
        public null|string|array $style = null,
    ) {
        $this->setPaths();
    }

    public function getViewTree(): HtmlViewTree
    {
        return Html(
            element: 'article',
            content: [
                $this->header ? Html('header', $this->header) : null,
                $this->content,
                $this->footer ? Html('footer', $this->footer) : null,
            ],
            attributes: [
                'class' => composeStr($this->class),
                'style' => composeStr($this->style),
            ],
        );
    }
}
