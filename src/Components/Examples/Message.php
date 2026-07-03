<?php

declare(strict_types=1);

namespace TempestPico\Components\Examples;

use TempestPico\Components\Message;

use function AKl\Tempest_HtmlView\content;

// don't use $impotent if Message inside a Accordion
return content(
    new Message('info', 'You may want to modify this component.'),
    new Message(
        'warning',
        'It make use of Pico vars and UnoCSS/Tailwind',
    ),
    new Message(
        'error',
        "Something went **not** wrong… 😁 \\
        *Just a Note:* This is a old component and the concept changed. A refactoring is planed.",
    ),
);
