<?php
namespace TempestPico\Components;

use Tempest\View\View;

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
