<?php

declare(strict_types=1);

namespace TempestPico\Components;

use Tempest\View\View;

#[Doc('Puts the content inside a `<article>` tag, Pico styles it card-like.', ['Pico'])]
final class Card implements View
{
    use IsView;

    public function __construct(
        public View $content,
        public ?View $header = null,
        public ?View $footer = null,
        public ?string $class = null,
        public ?string $style = null,
    ) {
        $this->setPaths();
    }
}
