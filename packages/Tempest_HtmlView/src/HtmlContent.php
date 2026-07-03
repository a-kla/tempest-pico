<?php

declare(strict_types=1);

namespace AKl\Tempest_HtmlView;

use AKl\Tempest_HtmlView\Exception\AttributesForNull;
use AKl\Tempest_HtmlView\Exception\InvalidCustomTag;
use AKl\Tempest_HtmlView\Exception\InvalidTag;
use AKl\Tempest_HtmlView\Exception\VoidWithContent;
use Dom\HTMLDocument;
use Stringable;
use Tempest\Support\Html\HtmlString;
use Tempest\View\Renderers\TempestViewRenderer;
use Tempest\View\View;

use function Tempest\get;
use function Tempest\report;
use function Tempest\Support\Arr\append;
use function Tempest\Support\Arr\filter;
use function Tempest\Support\Arr\is_empty as is_empty_arr;
use function Tempest\Support\Arr\map_iterable;
use function Tempest\Support\Html\format_attributes;
use function Tempest\Support\Html\is_html_tag;
use function Tempest\Support\Html\is_void_tag;
use function Tempest\Support\Str\wrap;

/**
 * Holds a tree of HTML elements and other HTML content and renders it to an HtmlString.
 *
 * @phpstan-type Content null|string|Stringable|HtmlString|HTMLDocument|View|self
 *
 * TODO:
 *  - appendAttribute()
 *  - style()? shortcut for adding classes and CSS properties (variables)
 */
// @mago-expect lint:cyclomatic-complexity
final class HtmlContent
{
    // Disallow Content
    public private(set) bool $isVoid = false;
    // has no Content
    public private(set) bool $isEmpty = true;

    private ?string $element = null;

    /** @var array<string, null|string|Stringable|bool> $attributes     */
    private array $attributes = [];

    /**
     * @var Content[] $children
     */
    private array $children = [];

    private self $current;

    public function __construct()
    {
        /*
         * WTF!
         * PhpStan:
         *
         * > Tempest\Support\Arr\ImmutableArray<(int|string),
         * >     Dom\HTMLDocument|string|Stringable|Tempest\View\View|AKl\Tempest_HtmlView\HtmlContent|null>
         * > does not accept
         * > Tempest\Support\Arr\ImmutableArray<(int|string),
         * >     Dom\HTMLDocument|string|Stringable|Tempest\View\View|AKl\Tempest_HtmlView\HtmlContent|null>.
         *
         * $this->children =
         * /** @var ImmutableArray<array-key, Content> * /
         * new ImmutableArray();
         */

        $this->current = &$this;
    }

    /**
     * @param Content|Content[] $content
     * @param array<string, null|string|Stringable|bool> $attributes
     * */
    public function __invoke(
        ?string $element,
        string|Stringable|HtmlString|View|HTMLDocument|self|array|null $content = null,
        array $attributes = [],
    ): self {
        if (! is_array($content)) {
            $content = [$content];
        }

        $this->appendElement(
            element: $element,
            content: $content,
            attributes: $attributes,
        );

        return $this;
    }

    /**
     * Does not check it is a HTML Element
     *
     * @param non-empty-string $customTag // TODO: needs to include '-'
     * @param Content|Content[] $content
     * @param array<string, null|string|Stringable|bool> $attributes
     */
    public function customTag(
        string $customTag,
        self|string|Stringable|HtmlString|HTMLDocument|View|array|null $content = null,
        array $attributes = [],
    ): self {
        /*
         * This regex is intentionally simplified.
         *
         * Emojis are allowed in custom tags, but I see not much practical use for them.
         * `<emoji-🤖></emoji-🤖>` vs `<svg-emoji name="🤖"></svg-emoji>`
         */
        if (! preg_match('/^[a-z][a-z0-9]+-[a-z0-9]+$/', $customTag)) {
            throw new InvalidCustomTag($customTag);
        }

        $node = new self();
        $node->element = $customTag;
        $node->attributes = $attributes;

        if (! is_null($content)) {
            if (is_array($content)) {
                $node->appendContent(...$content);
            } else {
                $node->appendContent($content);
            }
        }

        $this->appendNode($node);

        return $this;
    }

    /**
     * @internal use __invoke()
     * @param Content[] $content
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

        if (is_null($element)) {
            if (! is_empty_arr($attributes)) {
                // TODO: trigger a warning or stay silent?
                // This is likely harmless, so do not throw.
                report(new AttributesForNull(print_r($attributes, true)));
            }
        } else {
            if (is_void_tag($element)) {
                $node->isVoid = true;
            }

            if (! is_html_tag($element)) {
                throw new InvalidTag($element);
            }
        }

        $node->appendContent(...$content);
        $this->appendNode($node);

        return $node;
    }

    /**
     * @param Content ...$content
     * */
    public function appendContent(
        self|string|Stringable|HtmlString|HTMLDocument|View|null ...$content
    ): self {
        $content = filter($content);

        if (count($content) === 0) {
            return $this;
        }

        $node = &$this->current;

        if ($node->isVoid) {
            throw new VoidWithContent($node->element ?? 'null');
        }

        $node->children = append($node->children, ...$content);
        $node->isEmpty = false;

        return $this;
    }

    /**
     * @internal
     */
    private function appendNode(self $node): void
    {
        if ($this->current->isVoid) {
            throw new VoidWithContent($this->element ?? 'null');
        }

        $this->current->children = append($this->current->children, $node);
        $this->current->isEmpty = false;

        $this->current = &$node;
    }

    /**
     * @internal
     *
     * You probably want to use `$component->toHtml()`
     */
    public function render(): HtmlString
    {
        $node = &$this;

        if ($node->element === null && $node->isEmpty) {
            return new HtmlString();
        }

        $html = implode(
            '',
            map_iterable(
                $node->children,
                static fn (
                    string|Stringable|HtmlString|HTMLDocument|View|self|null $value,
                ) => match (true) {
                    $value === null => '',
                    $value instanceof HtmlString => $value,
                    $value instanceof self => $value->render(),
                    $value instanceof HtmlView => $value->toHtml(),
                    $value instanceof HTMLDocument => $value->body?->innerHTML,
                    $value instanceof View =>
                        get(TempestViewRenderer::class)->render($value),
                    default => escape((string) $value),
                },
            ),
        );

        if ($node->element === null) {
            return new HtmlString($html);
        }

        $attributes = $node->attributes ? format_attributes($node->attributes) : '';

        return new HtmlString(
            match (true) {
                // closing slash (' /') is not needed for void tags,
                $node->isVoid => "<{$node->element}{$attributes}>",
                // is_empty($html) && ! contains($node->element, '-') => "<{$node->element}{$attributes} />",
                default => wrap($html, "<{$node->element}{$attributes}>", "</{$node->element}>"),
            },
        );

        // @mago-expect lint:halstead
    }
}
