<?php

declare(strict_types=1);

namespace TempestPico\Components\Examples;

use TempestPico\Components\Table;

use function TempestPico\Support\Html\IMD;
use function TempestPico\Support\Html\VT;

return VT(
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
                'CSS' => IMD('**t**ext-**a**lign-**l**ast: **c**enter;'),
                'ACSS' => 'Tal(c)',
                // missing TW => fallback
            ],
        ],
        primaryRow: 'CSS',
        options: Table::Options(
            caption: 'Example using the Component',
            fallback: IMD("> I don't know"),
        ),
    ),
);
