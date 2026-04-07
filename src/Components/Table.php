<?php

declare(strict_types=1);

namespace TempestPico\Components;

use Tempest\View\View;
use TempestPico\Support\Html\HTMLBuilder;

use function Tempest\Support\Arr\has_key;
use function Tempest\Support\Arr\keys;
use function Tempest\Support\Arr\map_iterable;
use function TempestPico\Support\composeStr;
use function TempestPico\Support\Html;

/**
 * Generate a Table
 *
 * @phpstan-type Opt = array{
 *      caption: null|string,
 *      fallback: string|View,
 *
 *      striped: bool,
 *      scrollable: bool,
 *
 *      vertical: bool, // UNIMPLEMENTED
 * }
 */
#[Doc('A component that allows you to create tables.', ['Helper', 'Pico'])]
final class Table implements Component
{
    use IsComponent;

    /**
     *
     * @param array<string, string|Component> $head
     * @param array<string, string|Component>[] $cells
     * @mago-expect analysis:non-existent-class-like <= FIXME: Tell Mago that Otp is a type alias
     * @param Opt $options
     */
    public function __construct(
        public array $head,
        public array $cells,
        public string $primaryRow,
        public array $options,
    ) {
        $this->setPaths();
    }

    /**
     *
     * @return Opt
     *
     *  @mago-expect lint:no-boolean-flag-parameter
     */
    static function Options(
        ?string $caption = null,
        // Cell content if unset or null
        string|View $fallback = '',

        bool $striped = true,
        bool $scrollable = true,

        // swap rows and cols
        bool $vertical = false, // UNIMPLEMENTED
    ): array {
        return [
            'caption' => $caption,
            'fallback' => $fallback,

            'striped' => $striped,
            'scrollable' => $scrollable,

            'vertical' => $vertical,
        ];
    }

    public function getViewTree(): HTMLBuilder
    {
        $getCellContent = fn (array $row, string $rowId) => has_key($row, $rowId)
            ? $row[$rowId]
            : $this->options['fallback'];

        $rowIds = keys($this->head);

        return Html(
            element: 'table',
            content: [
                // TODO: `slot(name: 'caption', ?wrapper: 'caption', ?if_unset = null)`
                $this->options['caption'] ? Html('caption', [$this->options['caption']]) : null,
                Html(
                    'thead',
                    [Html(
                        'tr',
                        map_iterable(
                            $this->head,
                            static fn ($cell) => $cell instanceof View ? $cell : Html('th', [$cell]),
                        ),
                    )],
                ),
                Html(
                    'tbody',
                    map_iterable($this->cells, fn ($row, int $colId) => Html(
                        'tr',
                        map_iterable(
                            $rowIds, // not $row to force the right order
                            fn (string $rowId) => (
                                $rowId === $this->primaryRow
                                    ? Html('th', [$getCellContent($row, $rowId)], ['scope' => 'row'])
                                    : Html('td', [$getCellContent($row, $rowId)])
                            ),
                        ),
                    )
                        // TODO: slot('footer', 'tfoot', fn …)
                    ),
                ),
            ],
            attributes: [
                'class' => composeStr([
                    'striped' => $this->options['striped'],
                    'scrollable' => $this->options['scrollable'],
                ]),
            ],
        );
    }
}
