<?php

declare(strict_types=1);

namespace TempestPico\Components;

use Tempest\View\View;

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
final class Table implements View
{
    use IsView;

    /**
     *
     * @param array<string, string|View> $head
     * @param array<string, string|View>[] $cells
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
        // Content of a unset or null cell
        string|View $fallback = '',

        bool $striped = true,
        bool $scrollable = true,

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
}
