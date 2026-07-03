<?php

declare(strict_types=1);

namespace AKl\Tempest_HtmlView;

use AKl\Tempest_HtmlView\Exception\HtmlToDomFailed;
use Dom\HTMLDocument;
use Stringable;
use Tempest\Support\Html\HtmlString;
use Tempest\Support\Path\Path;
use Tempest\View\View;
use TempestPico\Components\InlineMarkdown;
use TempestPico\Components\Markdown;
use TempestPico\Components\PhpDom;

use function Tempest\report;
use function Tempest\Support\arr;
use function Tempest\Support\Arr\is_list;
use function Tempest\Support\Filesystem\read_file;
use function Tempest\Support\Str\ensure_ends_with;
use function Tempest\Support\Str\ensure_starts_with;

/**
 *
 * @param array<string, null|string|Stringable|bool> $attributes
 *
 * NOTE: This is a Mago style Type import, PhpStan don't has this feature
 * see https://github.com/phpstan/phpstan/issues/9164
 * @param !HtmlContent::Content|!HtmlContent::Content[] $content
 *
 **/
function Html(
    ?string $element,
    null|string|Stringable|HtmlString|HTMLDocument|HtmlContent|View|array $content = null,
    array $attributes = [],
): HtmlContent {
    return new HtmlContent()(
        element: $element,
        attributes: $attributes,
        content: $content,
    );
}

/**
 * Non-Html content will be escaped for safe output in HTML.
 *
 * @param !HtmlContent::Content|!HtmlContent::Content[] ...$content
 * */
function content(
    null|string|Stringable|HtmlString|HTMLDocument|HtmlContent|View|array ...$content,
): HtmlContent {
    // NOTE: Mago says $content is Content|Content[]|Content[]
    // @mago-expect analysis:possibly-invalid-argument
    return new HtmlContent()(content: $content, element: null);
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
function MD(string|Path $MdOrFile): HtmlContent
{
    $MdOrFile = is_string($MdOrFile)
        ? $MdOrFile
        : read_file($MdOrFile->toString());

    return new Markdown($MdOrFile)->template();
}

/** Shortcut to render Inline MarkDown */
function IMD(string $md): HtmlContent
{
    return new InlineMarkdown($md)->template();
}

// TODO: DomFromUrl() - cUrl or file_get_contents('https:/
/** Shortcut to create a view tree from an HTML string using PHP's Dom\HTMLDocument */
function Dom(
        string|Path $HtmlOrFile,
        // do not throw an error if the HTML is not well formed, try to fix it instead (default: true)
        bool $fixHtml = true,
    ): HTMLDocument
{

#    return new PhpDom($html);

        $code = match (true) {
            is_string($HtmlOrFile) => $HtmlOrFile,
            default => read_file($HtmlOrFile->toString()),
        };
        // Fix: don't use  LIBXML_HTML_NOIMPLIED: https://github.com/php/php-src/issues/19857
 //       $code = ensure_starts_with($code, '<!DOCTYPE html><html><body>');
//        $code = ensure_ends_with($code, '</body></html>');

        $previousInternalErrors = libxml_use_internal_errors(true);

        try {
            $document = HTMLDocument::createFromString($code, LIBXML_COMPACT);

            if ($fixHtml) {
                libxml_clear_errors();
            }

            return $document;
        } catch (\Throwable $th) {
            throw new HtmlToDomFailed($th, $code);
        } finally {
            libxml_clear_errors();
            libxml_use_internal_errors($previousInternalErrors);
        }
    }


/**
 * Composes a string from an array of strings and (if provided) their conditions.
 * If the condition is true, the string is included in the result.
 *
 * This can be useful for example when you want to add Css class on specific conditions.
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
 * @param null|string|string[]|array<string, bool|callable():bool> $strings
 */
function composeStr(null|string|array $strings, string $glue = " "): ?string
{
    if (is_null($strings)) {
        return null;
    }
    if (is_string($strings)) {
        $string = trim($strings);
        return $string === "" ? null : $string;
    }
    if (is_list($strings)) {
        return implode($glue, $strings);
    }

    /**
     * [for stupid LSPs like PHP Intelephense for VS Code] at this point:
     * @var array<string, bool|callable():bool>  $strings
     **/

    $string = arr($strings)
        // @mago-expect analysis:possibly-invalid-argument
        ->map(
            /** @param bool|callable():bool  $condition */
            static function (
                bool|callable $condition,
                string $string,
            ): ?string {
                if (is_callable($condition)) {
                    $condition = $condition();
                }
                return $condition ? $string : null;
            },
        )
        ->filter()
        ->keys()
        ->implode($glue)
        ->toString();

    return $string === "" ? null : $string;
}

function cssVar(null|string|int|Stringable ...$vars): ?string
{
    $string = arr($vars)
        ->filter()
        ->map(static function (
            string|Stringable|int $condition,
            string $string,
        ): string {
            return "--{$string}:{$condition}";

            // @mago-expect analysis:possibly-invalid-argument
        })
        ->implode(";")
        ->toString();

    return $string === "" ? null : $string;
}

/** Shortcut to import an HTML file as a DOMDocument
 * TODO: refactor
 */
function importHtmlFile(string $path): HTMLDocument
{
    return HTMLDocument::createFromFile(
        getRealPath($path),
        LIBXML_HTML_NOIMPLIED,
    );
}

/**
 * Resolves the given path to an absolute path.
 * If the path does not exist, an exception is thrown.
 *
 * @param string $path The path to resolve.
 * @return non-empty-string The resolved absolute path.
 * @throws \InvalidArgumentException If the path does not exist.
 */
function getRealPath(string $path): string
{
    $resolved = realpath($path);
    if (!$resolved) {
        throw new \InvalidArgumentException("Invalid path: {$path}");
    }
    return $resolved;
}
