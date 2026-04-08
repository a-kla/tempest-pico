<?php

declare(strict_types=1);

namespace TempestPico\Components;

use Tempest\Support\Arr\ImmutableArray;
use TempestPico\Support\Html\HtmlViewTree;

use function Tempest\Support\arr;
use function TempestPico\Support\Html;

#[Doc('Renders the given messages card-like. Type can be error, warning or info.', ['Custom'])]
final class Messages implements Component
{
    use IsComponent;

    /**
     * @var ImmutableArray<array-key, array{variant: 'error' | 'warning' | 'info' , md: string}> $msgs
     **/
    public ImmutableArray $msgs;

    /** @param array{variant: 'error' | 'warning' | 'info' , md: string} $msgs */
    public function __construct(
        array ...$msgs,
    ) {
        $this->msgs = arr($msgs);
        $this->setPaths();
    }

    public function getViewTree(): HtmlViewTree
    {
        return Html(
            element: null,
            content: $this->msgs
                ->map(static fn (array $msg) => match ($msg['variant']) {
                    'error' => new Card(
                        content: new Markdown($msg['md']),
                        header: new Markdown('**Error**'),
                        class: 'outline-4 outline-double outline-red',
                        style: '--pico-card-background-color: #ff000060;
                                --pico-card-border-color: rgb(248 113 113);
                                --pico-card-sectioning-background-color: #ff0000c0;
                                ',
                    ),
                    'warning' => new Card(
                        content: new Markdown($msg['md']),
                        header: new Markdown('**Warning**'),
                        class: ' border border-solid border-amber dark:border-amber-600',
                        style: '--pico-card-border-color: light-dark(#f2df0d, #e17100);
                                --pico-card-background-color: light-dark(#f2df0d33, #ffbf0033);
                                --pico-card-sectioning-background-color: light-dark(#f2df0d, #e17100);
                                ',
                    ),
                    'info' => new Card(
                        content: new Markdown($msg['md']),
                        style: '--pico-card-background-color: light-dark(#9bccfd, #1343a0)',
                    ),
                    default => new Card(
                        content: new Markdown($msg['md']),
                    ),
                })->toArray(),
        );
    }

    /*
     * public function toHtml(): HtmlString
     * {
     * /** @var ImmutableArray<array-key, Card> $cards * /
     * $cards = $this->msgs
     * ->map(static fn (array $msg) => match ($msg['variant']) {
     * 'error' => new Card(
     * content: new Markdown($msg['md']),
     * header: new Markdown('**Error**'),
     * class: 'outline-4 outline-double outline-red',
     * style: '                            --pico-card-background-color: #ff000060;
     * --pico-card-border-color: rgb(248 113 113);
     * --pico-card-sectioning-background-color: #ff0000c0;
     * ',
     * ),
     * 'warning' => new Card(
     * content: new Markdown($msg['md']),
     * header: new Markdown('**Warning**'),
     * class: ' border border-solid border-amber dark:border-amber-600',
     * style: '
     * --pico-card-border-color: light-dark(#f2df0d, #e17100);
     * --pico-card-background-color: light-dark(#f2df0d33, #ffbf0033);
     * --pico-card-sectioning-background-color: light-dark(#f2df0d, #e17100);
     * ',
     * ),
     * 'info' => new Card(
     * content: new Markdown($msg['md']),
     * style: '--pico-card-background-color: light-dark(#9bccfd, #1343a0)',
     * ),
     * 'default' => new Card(
     * content: new Markdown($msg['md']),
     * ),
     * });
     *
     * return new Stack(...$cards->values())->toHtml();
     * }
     */
}
