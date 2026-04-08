<?php

declare(strict_types=1);

namespace TempestPico\Support;

use Deprecated;
use Stringable;
use Tempest\Support\Html\HtmlString;
use Tempest\Support\Str\ImmutableString;
use Tempest\View\View;
use TempestPico\Components\Component;
use TempestPico\Support\Html\HtmlViewTree;

use function Tempest\Support\Arr\implode;

/**
 * @param array<string, null|string|Stringable|bool> $attributes
 * @param array<HtmlViewTree|string|Stringable|HtmlString|View|null> $content
 * */
function Html(?string $element, array $content = [], array $attributes = []): HtmlViewTree
{
    return (new HtmlViewTree())(element: $element, attributes: $attributes, content: $content);
}

/** View Tree */
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

/**
 * Renders the content of a view or a string.
 * If the content is a string, it will be escaped for safe output in HTML.
 *
 * @param string|View $content The content to render.
 * @return string The rendered content.
 */
#[Deprecated('Use maybeUnsafe()')]
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
 * @param null|string|array<string, bool|callable():bool> $check
 */
function composeStr(null|string|array $check): ?ImmutableString
{
    if ($check === null) {
        return null;
    }

    if (is_string($check)) {
        return new ImmutableString($check);
    }

    $strings = [];

    foreach ($check as $string => $isTrue) {
        if (is_callable($isTrue)) {
            $isTrue = $isTrue();
        }

        if (! $isTrue) {
            continue;
        }
        $strings[] = $string;
    }

    return implode($strings, ' ');
}
