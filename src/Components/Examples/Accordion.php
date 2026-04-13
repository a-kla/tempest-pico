<?php

declare(strict_types=1);

namespace TempestPico\Components\Examples;

use TempestPico\Components\Accordion;
use TempestPico\Components\Messages;

use function TempestPico\Support\Html\VT;

$content = [
    'Section #*1*' => "# Markdown\n\nThis is *Markdown*!",
    'Section #*2*' => new Messages(['variant' => 'info', 'md' => 'This is a Component']),
    'Section #*3*' => VT("# Markdown\n\nThis is **NOT** *Markdown*!"),
];

return VT(
    new Accordion('acco', $content),

    new Accordion(
        'acco',
        ['Button (primary)' => $content['Section #*1*']],
        Accordion::Options('button', 'primary'),
    ),
    new Accordion(
        'acco',
        ['Button (contrast outline)' => $content['Section #*2*']],
        Accordion::Options('button-outline', 'contrast'),
    ),
    new Accordion(
        'acco',
        ['Button (secondary)' => $content['Section #*3*']],
        Accordion::Options(btnColor: 'secondary'),
    ),

    new Accordion(
        'acco',
        ['h2' => $content['Section #*1*']],
        Accordion::Options('h2'),
    ),
    new Accordion(
        'acco',
        ['h3' => $content['Section #*2*']],
        Accordion::Options('h3'),
    ),
    new Accordion(
        'acco',
        ['h4' => $content['Section #*3*']],
        Accordion::Options('h4'),
    ),
);
