<?php

declare(strict_types=1);

namespace TempestPico\Support\Html;

use Stringable;
use Tempest\Support\Arr\ImmutableArray;
use Tempest\Support\Html\HtmlString;
use Tempest\View\View;
use TempestPico\Components\Component;

use function Tempest\report;
use function Tempest\Support\arr;
use function Tempest\Support\Arr\filter;
use function Tempest\Support\Html\format_attributes;
use function Tempest\Support\Html\is_html_tag;
use function Tempest\Support\Html\is_void_tag;
use function Tempest\Support\Str\is_empty;
use function Tempest\Support\Str\wrap;
use function TempestPico\Support\escape;

/**
 * Build and render a Abstract Html Tree
 *
 *
 */
final class HTMLBuilder
{
    // Disallow Content
    public private(set) bool $isVoid = false;
    // has no Content
    public private(set) bool $isEmpty = true;

    private ?string $element = null;

    /** @var array<string, null|string|Stringable|bool> $attributes     */
    private array $attributes = [];

    /** @var ImmutableArray<int, self|string|Stringable|HtmlString|View|null> $children aka content */
    private ImmutableArray $children;

    private self $current;

    public function __construct()
    {
        $this->children = new ImmutableArray();
        $this->current = &$this;
        return;
    }

    /**
     * @param array<self|string|Stringable|HtmlString|View> $content
     * @param array<string, null|string|Stringable|bool> $attributes
     */
    private function appendElement(
        ?string $element = null,
        array $content = [],
        array $attributes = [],
    ): self {
        $node = new self();
        $node->element = $element;
        $node->attributes = $attributes;

        if ($element === null) {
            try {
                if (arr($attributes)->isNotEmpty()) {
                    throw new Exception\AttributesForNull($attributes);
                }
            } catch (Exception\AttributesForNull $e) {
                // Just warn
                report($e);
            }
        } else {
            if (is_void_tag($element)) {
                $node->isVoid = true;
            }
            if (! is_html_tag($element)) {
                throw new Exception\InvalidTag($this->element ?? '(null)');
            }
        }

        $node->appendContent(...$content);
        $this->appendNode($node);
        return $node;
    }

    /**
     * Dos not check it is a HTML Element
     *
     * @param array<self|string|Stringable|HtmlString|View> $content
     * @param array<string, null|string|Stringable|bool> $attributes
     */
    public function customTag(
        string $customTag,
        array $content = [],
        array $attributes = [],
    ): self {
        $node = new self();
        $node->element = $customTag;
        $node->attributes = $attributes;
        $node->appendContent(...$content);
        $this->appendNode($node);
        return $this;
    }

    /**
     * @template T of self|string|Stringable|HtmlString|View
     * @param T ...$content
     * */
    public function appendContent(self|string|Stringable|HtmlString|View|null ...$content): self
    {
        $node = &$this->current;

        $content = filter($content);

        if ($node->isVoid && count($content) > 0) {
            throw new Exception\VoidWithContent($node->element);
        }

        if (count($content) === 0) {
            return $this;
        }

        $node->children = $node->children->append(...$content);

        $node->isEmpty = false;

        return $this;
    }

    private function appendNode(self $node): void
    {
        if ($this->current->isVoid) {
            throw new Exception\VoidWithContent($this->element);
        }

        $this->current->children = $this->current->children->append($node);
        $this->current->isEmpty = false;

        $this->current = &$node;
    }

    /**
     * @param array<self|string|Stringable|HtmlString|View> $content
     * @param array<string, null|string|Stringable|bool> $attributes
     * */
    public function __invoke(?string $element, array $content = [], array $attributes = []): self
    {
        // $node = $this->current ?? $this;

        // $this->current =
        $this->appendElement(element: $element, content: $content, attributes: $attributes);

        return $this;
    }

    public function render(): HtmlString
    {
        $node = &$this;

        if ($node->element === null && $node->isEmpty) {
            return new HtmlString();
        }

        $attributes = $node->attributes ? format_attributes($node->attributes) : '';

        // if ($node->children->isEmpty()) {
        //     return new HtmlString("<{$node->element}{$attributes} />");
        // }

        $html = $node->children->map(
            static function ($value, $_) {
                return match (true) {
                    $value instanceof HtmlString => $value,
                    $value instanceof self => $value->render(),
                    $value instanceof Component => $value(),
                    $value instanceof View => '- FIXME: How to implement it? -',
                    default => escape((string) $value),
                };
            },
        )->implode('');

        if ($node->element === null) {
            return new HtmlString($html);
        }

        $html = is_empty($html)
            ? "<{$node->element}{$attributes} />"
            : wrap($html, "<{$node->element}{$attributes}>", "</{$node->element}>");

        return new HtmlString($html);
    }

    /*
     * public function __toString(): string
     * {
     * var_dump($this);
     * return $this->render();
     * }
     */
}
