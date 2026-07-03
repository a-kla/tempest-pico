<?php

declare(strict_types=1);

namespace TempestPico\Components\Examples;

use TempestPico\Components\Accordion;
use TempestPico\Components\Message;

use function AKl\Tempest_HtmlView\content;

$content = [
    'Section #*1*' => "# Markdown\n\nThis is *Markdown*!",
    'Section #*2*' => new Message('info', 'This is a Component'),
    'Section #*3*' => content("# Markdown\n\nThis is **NOT** *Markdown*!"),
];

$id = 'accordion-example';

return content(
    new Accordion($id, $content),

    new Accordion(
        $id,
        ['Variant: h2' => $content['Section #*1*']],
        Accordion::Options('h2'),
    ),
    new Accordion(
        $id,
        ['Variant: h3' => $content['Section #*2*']],
        Accordion::Options('h3'),
    ),
    new Accordion(
        $id,
        ['Variant: h4' => $content['Section #*3*']],
        Accordion::Options('h4'),
    ),

    new Accordion(
        $id,
        ['Variant: Button (primary)' => $content['Section #*1*']],
        Accordion::Options('button', 'primary'),
    ),
    new Accordion(
        $id,
        ['Variant: Button (secondary outline)' => $content['Section #*2*']],
        Accordion::Options('button-outline', 'secondary'),
    ),
    new Accordion(
        $id,
        ['Variant: Button (contrast)' => $content['Section #*3*']],
        Accordion::Options(variant: 'button', btnColor: 'contrast'),
    ),
);
