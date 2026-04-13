<?php

declare(strict_types=1);

namespace TempestPico\Components;

use TempestPico\Support\Html\HtmlViewTree;

use function Tempest\Support\Arr\map_iterable;
use function TempestPico\Support\Html\composeStr;
use function TempestPico\Support\Html\Html;
use function TempestPico\Support\Html\IMD;
use function TempestPico\Support\Html\MD;
use function TempestPico\Support\Html\VT;

/**
 *
 * @phpstan-type Opt = array{
 *      variant: 'default'|'button-outline'|'button'|'h6'|'h5'|'h4'|'h3'|'h2',
 *      btn-color: 'primary'|'secondary'|'contrast',
 *      open: false|IMD,
 * }
 *
 */
#[Doc('Use `<details>` Element to toggle sections of content without JavaScript.', ['Pico'])]
final class Accordion implements Component
{
    use IsComponent;

    /**
     * @param Opt $options
     * @param array<IDM, MD|Content> $content
     */
    public function __construct(
        protected string $name,
        protected array $content,
        protected ?array $options = null,
    ) {
        $this->options ??= self::Options();
        $this->setPaths();
    }

    /**
     * Create an options array for the constructor.
     *
     * @param Opt['variant'] $variant
     * @param Opt['btn-color'] $btnColor
     * @param Opt['open'] $open The index (IMD) of the open section
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

    private function subViewSummary(string $summary): HtmlViewTree
    {
        return Html(
            element: in_array(
                $this->options['variant'],
                ['h6', 'h5', 'h4', 'h3', 'h2'],
                true,
            )
                ? $this->options['variant']
                : null,
            content: IMD($summary),
        );
    }

    public function getViewTree(): HtmlViewTree
    {
        return VT(...map_iterable(
            $this->content,
            fn ($content, $summary) => Html(
                element: 'details',
                content: [
                    Html(
                        'summary',
                        [$this->subViewSummary((string) $summary)], // FIXME:  PhpStan says $summary is `int|string`
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
        ));
    }
}
