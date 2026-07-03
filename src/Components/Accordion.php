<?php

declare(strict_types=1);

namespace TempestPico\Components;

use AKl\Tempest_HtmlView\HtmlContent;
use AKl\Tempest_HtmlView\HtmlView;

use function AKl\Tempest_HtmlView\composeStr;
use function AKl\Tempest_HtmlView\content;
use function AKl\Tempest_HtmlView\Html;
use function AKl\Tempest_HtmlView\IMD;
use function AKl\Tempest_HtmlView\MD;
use function Tempest\Support\Arr\map_iterable;

/**
 * @phpstan-type MD_String = string
 * @phpstan-type iMD_String = string
 * @type MD_String = string
 * @type iMD_String = string
 *
 * @phpstan-import-type Content from HtmlContent
 *
 * @phpstan-type Opt = array{
 *      variant: 'default'|'button-outline'|'button'|'h6'|'h5'|'h4'|'h3'|'h2',
 *      btn-color: 'primary'|'secondary'|'contrast',
 *      open: false|iMD_String,
 * }
 *
 */
#[
    Doc(
        'Use `<details>` Element to toggle sections of content without JavaScript.',
        ['Pico'],
    ),
]
final class Accordion extends HtmlView
{
    /** @var Opt  */
    private array $options;

    /**
     * @param array<iMD_String, MD_String|Content> $content
     * @param Opt $options
     */
    public function __construct(
        private readonly string $name,
        private readonly array $content,
        ?array $options = null,
    ) {
        parent::__construct();
        $this->options = $options ?? self::Options();
    }

    /**
     * Create an options array for the constructor.
     *
     * @param Opt['variant'] $variant
     * @param Opt['btn-color'] $btnColor
     * @param Opt['open'] $open The index (iMD_String) of the open section
     *
     * @return Opt
     *
     */
    static function Options(
        string $variant = 'default',
        string $btnColor = 'primary',
        false|string $open = false,
    ): array {
        return [
            'variant' => $variant,
            'btn-color' => $btnColor,
            'open' => $open,
        ];
    }

    private function templateSummary(string $summary): HtmlContent
    {
        return Html(
            in_array(
                $this->options['variant'],
                ['h6', 'h5', 'h4', 'h3', 'h2'],
                true,
            )
                ? $this->options['variant']
                : null,
            content: IMD($summary),
        );
    }

    public function template(): HtmlContent
    {
        return content(
            ...map_iterable(
                $this->content,
                fn ($content, string $summary): HtmlContent => Html(
                    element: 'details',
                    content: [
                        Html(
                            'summary',
                            [$this->templateSummary($summary)], // FIXME:  PhpStan says $summary is `int|string`
                            [
                                'role' => in_array(
                                    $this->options['variant'],
                                    ['button', 'button-outline'],
                                    true,
                                )
                                    ? 'button'
                                    : false,
                                'class' => composeStr([
                                    'outline' => $this->options['variant'] === 'button-outline',
                                    $this->options['btn-color'] => $this->options['variant'] !== 'default',
                                ]),
                            ],
                        ),
                        is_string($content) ? MD($content) : $content,
                    ],
                    attributes: [
                        'name' => $this->name,
                        'open' => $this->options['open'] !== false && $this->options['open'] === $summary,
                    ],
                ),
            ),
        );
    }
}
