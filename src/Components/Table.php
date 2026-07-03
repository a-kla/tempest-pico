<?php

declare(strict_types=1);

namespace TempestPico\Components;

use AKl\Tempest_HtmlView\HtmlContent;
use AKl\Tempest_HtmlView\HtmlView;

use function AKl\Tempest_HtmlView\composeStr;
use function AKl\Tempest_HtmlView\Html;
use function Tempest\Support\Arr\has_key;
use function Tempest\Support\Arr\keys;
use function Tempest\Support\Arr\map_iterable;

/**
 * Generate a Table
 * @phpstan-type Opt = array{
 *      caption: null|string,
 *      fallback: string|HtmlContent|null,
 *      striped: bool,
 *      scrollable: bool,
 *      vertical: bool, // UNIMPLEMENTED
 * }
 */
#[Doc('A component that allows you to create tables.', ['Helper', 'Pico'])]
final class Table extends HtmlView
{
    /**
     *
     * @param array<string, !HtmlContent::Content> $head
     * @param array<string, !HtmlContent::Content>[] $cells
     * @param Opt $options
     */
    public function __construct(
        public array $head,
        public array $cells,
        public string $primaryRow,
        public array $options,
    ) {
        parent::__construct();
    }

    /**
     * @param Opt['caption'] $caption
     * @param Opt['fallback'] $fallback
     * @param Opt['striped'] $striped
     * @param Opt['scrollable'] $scrollable
     * @param Opt['vertical'] $vertical
     *
     * @return Opt
     *
     *  @mago-expect lint:no-boolean-flag-parameter
     */
    static function Options(
        ?string $caption = null,
        // Cell content if unset or null
        string|HtmlContent|null $fallback = '',

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

    public function template(): HtmlContent
    {
        $getCellContent = fn (
            array $row,
            string $rowId,
        ): string|HtmlContent|null => has_key($row, $rowId)
            ? $row[$rowId]
            : // @mago-expect analysis:mixed-return-statement
            $this->options['fallback'];

        $rowIds = keys($this->head);

        return Html(
            'table',
            attributes: [
                'class' => composeStr([
                    'striped' => $this->options['striped'],
                    'scrollable' => $this->options['scrollable'],
                ]),
            ],
            content: [
                // TODO: `slot(name: 'caption', ?wrapper: 'caption', ?if_unset = null)` ?
                $this->options['caption']
                    ? Html('caption', $this->options['caption'])
                    : null,
                Html('thead', [
                    Html(
                        'tr',
                        map_iterable(
                            $this->head,
                            static fn (
                                string|HtmlContent $cell,
                            ): HtmlContent => (
                                is_string($cell)
                                    ? Html('th', [$cell])
                                    : $cell
                            ),
                        ),
                    ),
                ]),
                Html(
                    'tbody',
                    map_iterable(
                        $this->cells,
                        fn ($row): HtmlContent => Html(
                            'tr',
                            map_iterable(
                                $rowIds, // not $row to force the right order
                                fn (string $rowId): HtmlContent => (
                                    $rowId === $this->primaryRow
                                        // @mago-expect analysis:mixed-argument
                                        ? Html('th', $getCellContent($row, $rowId), [
                                            'scope' => 'row',
                                        ])
                                        // @mago-expect analysis:mixed-argument
                                        : Html('td', $getCellContent($row, $rowId))
                                ),
                            ),
                        ),
                        // TODO: slot('footer', 'tfoot', fn …)
                    ),
                ),
            ],
        );
    }
}
