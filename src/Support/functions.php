<?php

declare(strict_types=1);

namespace TempestPico\Support;

use Tempest\Support\Str\ImmutableString;
use Tempest\View\View;

use function Tempest\Support\Arr\implode;

function _(string $s): string
{
    return htmlspecialchars($s);
}

function renderContent(string|View $content): string
{
    /*
     * @mago-expect analysis:mixed-return-statement
     * @phpstan-ignore callable.nonCallable
     */
    return $content instanceof View ? $content() : htmlspecialchars($content);
}

/**
 * Composes a string from an array of strings and their conditions. If the condition is true, the string is included in the result.
 *
 * This is useful for composing class strings from an array of class names and their conditions.
 * Example:
 * ```php
 * $class = composeStr([
 *     'border' => $useBorder,
 *     'border-color-red' => $useBorder && isAdmin(),
 *     'border-color-blue' => $useBorder && ! isAdmin(),
 * ]);
 * ```
 *
 * @param array<string, bool|callable():bool> $check
 */
function composeStr(array $check): ImmutableString
{
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
