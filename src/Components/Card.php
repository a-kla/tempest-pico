<?php

declare(strict_types=1);

namespace TempestPico\Components;

use Tempest\Support\Html\HtmlString;
use TempestPico\Support\Html\HtmlViewTree;

use function TempestPico\Support\composeStr;
use function TempestPico\Support\Html;

#[Doc('Puts the content inside a `<article>` tag, Pico styles it card-like.', ['Pico'])]
final class Card implements Component
{
    use IsComponent;

    /**
     * @param null|string|array<string, bool|callable(): bool> $class
     * @param null|string|array<string, bool|callable(): bool> $style
     **/
    public function __construct(
        public HtmlString|Component $content,
        public null|HtmlString|Component $header = null,
        public null|HtmlString|Component $footer = null,
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
                $this->header ? Html('header', [$this->header]) : null,
                $this->content,
                $this->footer ? Html('footer', [$this->footer]) : null,
            ],
            attributes: [
                'class' => composeStr($this->class),
                'style' => composeStr($this->style),
            ],
        );
    }

    /*
     * public function toHtml(): HtmlString
     * {
     * $asHtml = static fn (HtmlString|Component $content) => $content instanceof Component ? $content->toHtml() : $content;
     * $slots = arr([
     * 'header' => $this->header,
     * 'content' => $this->content,
     * 'footer' => $this->footer,
     * ])
     * ->filter(static fn (null|HtmlString|Component $slotContent) => $slotContent !== null)
     * ->map(
     * static function ($content, $name) use ($asHtml) {
     * if ($name === 'content') {
     * return $asHtml($content);
     * }
     *
     * return create_tag(
     * $name,
     * content: $asHtml($content)->toString(),
     * );
     * },
     * )
     * ->implode('');
     *
     * return create_tag(
     * 'article',
     * [
     * 'class' => composeStr($this->class),
     * 'style' => composeStr($this->style),
     * ],
     * $slots->toString(),
     * );
     * }
     */
}
