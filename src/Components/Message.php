<?php

declare(strict_types=1);

namespace TempestPico\Components;

use TempestPico\Support\Html\HtmlViewTree;

use function TempestPico\Support\Html\IMD;
use function TempestPico\Support\Html\MD;
use function TempestPico\Support\Html\VT;

#[Doc('Renders the given messages card-like. Implemented Types: error, warning and info.', ['Custom'])]
final class Message implements Component
{
    use IsComponent;

    /**
     * @param  'error' | 'warning' | 'info'|string $variant
     * @param string $md
     *
     */
    public function __construct(
        private string $variant,
        private string $md,
    ) {
        $this->setPaths();
    }

    public function getViewTree(): HtmlViewTree
    {
        return VT(match ($this->variant) {
            'error' => new Card(
                content: MD($this->md),
                header: IMD('**Error**'),
                class: 'outline-4 outline-double outline-red',
                style: '--pico-card-background-color: #ff000060;
                                --pico-card-border-color: rgb(248 113 113);
                                --pico-card-sectioning-background-color: #ff0000c0;
                                ',
            ),
            'warning' => new Card(
                content: MD($this->md),
                header: IMD('**Warning**'),
                class: ' border border-solid border-amber dark:border-amber-600',
                style: [
                    '--pico-card-border-color: light-dark(#f2df0d, #e17100);',
                    '--pico-card-background-color: light-dark(#f2df0d33, #ffbf0033);',
                    '--pico-card-sectioning-background-color: light-dark(#f2df0d, #e17100);',
                ],
            ),
            'info' => new Card(
                // @mago-expect analysis:mixed-argument
                content: MD($this->md),
                style: '--pico-card-background-color: light-dark(#9bccfd, #1343a0)',
            ),
            default => new Card(
                content: MD($this->md),
            ),
        });
    }
}
