<?php

declare(strict_types=1);

namespace AKl\Tempest_HtmlView;

use Tempest\Support\Html\HtmlString;
use AKl\Tempest_HtmlView\AssetManager;
use AKl\Tempest_HtmlView\HtmlView;
use AKl\Tempest_HtmlView\HtmlContent;

use function Tempest\get;
use function Tempest\Support\Arr\flatten;
use function Tempest\Support\Arr\has_key;
use function Tempest\Support\Arr\is_empty;
use function Tempest\Support\Arr\map_iterable;
use function AKl\Tempest_HtmlView\Html;
use function AKl\Tempest_HtmlView\content;

/** The Base View for all HTML Documents */
abstract class BaseView extends HtmlView
{
    protected ?HtmlString $content = null;

    /**
     * @var array<string, string> $meta
     */
    protected array $meta;

    protected(set) AssetManager $assets;

    /**
     * @param array<string, string> $meta // ! No description|robots => robots = noindex,nofollow
     */
    public function __construct(
        private(set) string $title,
        array $meta,
        private(set) string $language = 'en',
    ) {
        parent::__construct();
        $this->assets = get(AssetManager::class);

        if (! has_key($meta, 'description') && ! has_key($meta, 'robots')) {
            $this->meta['robots'] = 'noindex,nofollow';
        }

        $this->meta['viewport'] = 'width=device-width, initial-scale=1';
        $this->meta['color-scheme'] = 'light dark';

        $this->meta = [
            ...$this->meta,
            ...$meta,
        ];
    }

    public function template(): HtmlContent
    {
        $importMap = $this->assets->generateImportMap();
        if ($importMap) {
            $importMap = Html(
                'script',
                new HtmlString($importMap), // FIXME: this is JsString or DataString
                ['type' => 'importmap'],
            );
        }

        $cssImports = flatten(
            map_iterable(
                $this->assets->css,
                fn (iterable $styles, $layer): array => map_iterable(
                    // @mago-expect analysis:template-constraint-violation
                    // @mago-expect analysis:possibly-invalid-argument
                    // @mago-expect analysis:less-specific-nested-argument-type
                    $styles,
                    fn (string $name): string => "@import \"{$this->assets->use($name)}\" layer({$layer});",
                ),
            ),
            // ->join("\n", '') // Object of class Tempest\Support\Arr\ImmutableArray could not be converted to string
        );

        // \dd($cssImports);

        if (! is_empty($cssImports)) {
            $cssImports = Html('style', new HtmlString(
                // @mago-expect analysis:less-specific-nested-argument-type
                "@layer base, global, components, utilities;\n" . \implode("\n", $cssImports),
            ));
        }

        return content(
            new HtmlString('<!doctype html>'),
            Html(
                'html',
                [
                    Html(
                        'head',
                        [
                            Html('title', $this->title),
                            Html('meta', attributes: ['charset' => 'utf-8']),
                            $cssImports,
                            $importMap,
                            ...map_iterable(
                                $this->meta,
                                static fn ($value, $key): HtmlContent => Html('meta', attributes: ['name' => $key, 'content' => $value]),
                            ),
                            /*                            ...map_iterable(
                             * $this->assets->versioned,
                             * static fn ($value): ?HtmlContent => match ($value['type']) {
                             * 'CSS' => Html('link', attributes: ['rel' => 'stylesheet', 'href' => $value['target']]),
                             * default => null,
                             * },
                             * ),
                             */
                        // @mago-expect analysis:possibly-invalid-argument
                        ],
                    ),

                    Html(
                        'body',
                        [
                            $this->content,

                            ...map_iterable(
                                $this->assets->scripts,
                                static fn ($_, $uri): HtmlContent => Html('script', ' ', ['src' => $uri]),
                            ),
                            ...map_iterable(
                                $this->assets->versioned,
                                static fn ($value): ?HtmlContent => match ($value['type']) {
                                    'Script' => Html('script', attributes: ['src' => $value['target']]),
                                    'Module' => Html('script', attributes: ['src' => $value['target'], 'type' => 'module', 'crossorigin' => 'anonymous']),
                                    default => null,
                                },
                            ),
                        ],
                    ),
                ],
                [
                    'lang' => $this->language,
                ],
            ),
        );
    }

    #[\Override]
    public function toHtml(): HtmlString
    {
        $this->assets->copyAssets();
        $this->content = $this->template()->render();
        return self::template()->render();
    }
}
