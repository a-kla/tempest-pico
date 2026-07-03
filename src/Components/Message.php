<?php

declare(strict_types=1);

namespace TempestPico\Components;

use AKl\Tempest_HtmlView\HtmlContent;
use AKl\Tempest_HtmlView\HtmlView;

use function AKl\Tempest_HtmlView\content;
use function AKl\Tempest_HtmlView\IMD;
use function AKl\Tempest_HtmlView\MD;

#[
    Doc(
        'Renders the given messages card-like. Implemented Types: error, warning and info.',
        ['Custom'],
    ),
]
final class Message extends HtmlView
{
    /**
     * @param  'error' | 'warning' | 'info'|string $variant
     *
     */
    public function __construct(
        private readonly string $variant,
        private readonly string $md,
        private readonly bool $impotent = false,
    ) {
        parent::__construct();
    }

    private function closeButton(): ?HtmlView
    {
        if (! $this->impotent) {
            return null;
        }
        return new PhpDom(
            <<<'HTML'
                <form method="dialog">
                    <button autofocus>❌ OK</button>
                </form>
                HTML,
        );
    }

    public function template(): HtmlContent
    {
        $card = match ($this->variant) {
            'error' => new Card(
                content: MD($this->md),
                header: IMD('**Error**'),
                footer: $this->closeButton(),
                class: 'outline-4 outline-double outline-red',
                style: [
                    '--pico-card-background-color: #ff000060;',
                    '--pico-card-border-color: rgb(248 113 113);',
                    '--pico-card-sectioning-background-color: #ff0000c0;',
                ],
            ),
            'warning' => new Card(
                content: MD($this->md),
                header: IMD('**Warning**'),
                footer: $this->closeButton(),
                class: ' border border-solid border-amber dark:border-amber-600',
                style: [
                    '--pico-card-border-color: light-dark(#f2df0d, #e17100);',
                    '--pico-card-background-color: light-dark(#f2df0d33, #ffbf0033);',
                    '--pico-card-sectioning-background-color: light-dark(#f2df0d, #e17100);',
                ],
            ),
            'info' => new Card(
                content: MD($this->md),
                footer: $this->closeButton(),
                style: '--pico-card-background-color: light-dark(#9bccfd, #1343a0)',
            ),
            default => new Card(
                content: MD($this->md),
                footer: $this->closeButton(),
            ),
        };

        if (! $this->impotent) {
            return content($card);
        }

        return content(new Modal(content($card)));
    }
}
