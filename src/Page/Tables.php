<?php

declare(strict_types=1);

namespace TempestPico\Page;

use Tempest\Router\Get;
use TempestPico\Components\Markdown;
use TempestPico\Components\Stack;
use TempestPico\Components\Table;
use TempestPico\Layout\Page;

final readonly class Tables
{
    #[Get('/tables')]
    public function __invoke(): Page
    {
        return new Page(
            'Component Example: Table',
            new Stack([
                new Markdown(
                    <<<'MD'
                        # Example Tables

                        You can (ab)use Markdown to create Tables:

                        {.striped}
                        | CSS | ACSS class | Tailwind class |
                        | :-------: | :------ | -------: |
                        | color:red; | C(red) | text-red |
                        | text-align: justify; | Ta(j) | text-justify |
                        | text-align-last: center; | Tal(c) | {I don't know} |

                        Much simpler is using the Component:

                        MD,
                ),
                new Table(
                    // rowId => Header Text
                    head: [
                        'CSS' => 'CSS',
                        'ACSS' => 'ACSS class',
                        'TW' => 'Tailwind class',
                    ],
                    cells: [
                        [
                            'ID' => '34', // ignored (RowId is not in Head)
                            'CSS' => 'color:red;',
                            'ACSS' => 'C(red)',
                            'TW' => 'text-red',
                        ],
                        [
                            // no need to order
                            'ACSS' => 'Ta(j)',
                            'CSS' => 'text-align: justify;',
                            'TW' => 'text-justify',
                        ],
                        [
                            // you can use Views
                            'CSS' => new Markdown('**t**ext-**a**lign-**l**ast: **c**enter;'),
                            'ACSS' => 'Tal(c)',
                            // missing TW
                        ],
                    ],
                    primaryRow: 'CSS',
                    options: Table::Options(
                        caption: 'Example using the Component',
                        fallback: new Markdown("> I don't know"),
                    ),
                ),
            ]),
        );
    }
}
