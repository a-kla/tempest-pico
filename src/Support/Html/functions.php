<?php

declare(strict_types=1);

namespace TempestPico\Support\Html;

use Deprecated;
use Stringable;
use Tempest\Support\Html\HtmlString;
use Tempest\View\View;
use TempestPico\Components\Component;
use TempestPico\Components\InlineMarkdown;
use TempestPico\Components\Markdown;

use function Tempest\Support\Arr\filter;
use function Tempest\Support\Arr\implode;
use function Tempest\Support\Arr\is_empty;
use function Tempest\Support\Arr\is_list;

/**
 * @param array<string, null|string|Stringable|bool> $attributes
 * @param VT $content
 * */
function Html(?string $element, HtmlViewTree|string|Stringable|HtmlString|View|null|array $content = [], array $attributes = []): HtmlViewTree
{
    return (new HtmlViewTree())(element: $element, attributes: $attributes, content: $content);
}

/** View Tree
 * Use `VT()` to create a view tree from an array of content. It is a shortcut for `Html(null, $content);`
 * Non-Html content will be escaped for safe output in HTML.
 */
function VT(HtmlViewTree|string|Stringable|HtmlString|View|null ...$content): HtmlViewTree
{
    return (new HtmlViewTree())(content: $content, element: null);
}

/**
 * Escapes a string for safe output in HTML.
 * using htmlspecialchars()
 *
 * @example
 * ```php
 * use TempestPico\Support\escape as _;
 *
 * $name = "<script>alert('XSS');</script>";
 * ?>
 * <p>Hello, <?= _( $name ) ?>!</p>
 * ```
 *
 * @param string $s The string to escape.
 */
function escape(string $s): HtmlString
{
    return new HtmlString(htmlspecialchars($s));
}

/** Shortcut to render MarkDown */
function MD(string $md): HtmlViewTree
{
    return new Markdown($md)->getViewTree();
}

/** Shortcut to render Inline MarkDown */
function IMD(string $md): HtmlViewTree
{
    return new InlineMarkdown($md)->getViewTree();
}

/**
 * Renders the content of a view or a string.
 * If the content is a string, it will be escaped for safe output in HTML.
 *
 * @param string|View $content The content to render.
 * @return string The rendered content.
 */
#[Deprecated('Use VT()')]
function renderContent(string|View $content): string
{
    /*
     * @mago-expect analysis:mixed-return-statement
     * @phpstan-ignore callable.nonCallable
     */
    return $content instanceof View ? $content() : escape($content);
}

/**
 * Converts the given $content to an HtmlString.
 * If it not a Component or an HtmlString, it will be escaped for safe output in HTML.
 */
#[Deprecated('Use VT()')]
function toHtml(Component|HtmlString|Stringable|string|int|float $content): HtmlString
{
    if ($content instanceof HtmlString) {
        return $content;
    }

    if ($content instanceof Component) {
        return $content->toHtml();
    }

    return escape((string) $content);
}

/**
 * Composes a string from an array of strings and their conditions. If the condition is true, the string is included in the result.
 *
 * This is useful for composing class strings from an array of class names and their conditions.
 *
 * Example:
 * ```php
 * $useBorder = true;
 * function isAdmin(): bool { return false; }
 *
 * $class = composeStr([
 *     'border' => $useBorder,
 *     'border-color-red' => $useBorder && isAdmin(),
 *     'border-color-blue' => $useBorder && ! isAdmin(),
 * ]);
 *
 * echo $class; // "border border-color-blue"
 * ```
 *
 * @param null|string|string[]|array<string, bool|callable():bool> $check
 */
function composeStr(null|string|array $check): false|string
{
    ll($check);
    switch (true) {
        case is_null($check):
            return false;

        case is_string($check):
            return $check === '' ? false : $check;

        case is_list($check):
            return implode($check, ' ')->toString();

        default:
            $strings = [];

            foreach ($check as $string => $isTrue) {
                if (is_callable($isTrue)) {
                    $isTrue = $isTrue();
                }

                if ($isTrue) {
                    $strings[] = $string;
                }
            }

            $strings = filter($strings);

            return is_empty($strings) ? false : implode($strings, ' ')->toString();
    }
}
