<?php

declare(strict_types=1);

namespace TempestPico\Components;

use AKl\Tempest_HtmlView\HtmlContent;
use AKl\Tempest_HtmlView\HtmlView;

use function AKl\Tempest_HtmlView\composeStr;
use function AKl\Tempest_HtmlView\Html;

#[
    Doc(
        'Puts the content inside a `<article>` tag, Pico styles it card-like.',
        ['Pico'],
    ),
]
final class Card extends HtmlView
{
    /**
     * @param null|string|string[]|array<string, bool|callable(): bool> $class
     * @param null|string|string[]|array<string, bool|callable(): bool> $style
     **/
    public function __construct(
        public string|HtmlView|HtmlContent $content,
        public string|HtmlView|HtmlContent|null $header = null,
        public string|HtmlView|HtmlContent|null $footer = null,
        public string|array|null $class = null,
        public string|array|null $style = null,
    ) {
        parent::__construct();
    }

    public function template(): HtmlContent
    {
        return Html(
            'article',
            content: [
                $this->header ? Html('header', $this->header) : null,
                $this->content,
                $this->footer ? Html('footer', $this->footer) : null,
            ],
            attributes: [
                // @mago-expect analysis:less-specific-argument
                'class' => composeStr($this->class),
                // @mago-expect analysis:less-specific-argument
                'style' => composeStr($this->style),
            ],
        );
    }
}
