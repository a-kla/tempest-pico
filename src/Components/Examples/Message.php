<?php

declare(strict_types=1);

namespace TempestPico\Components\Examples;

use TempestPico\Components\Message;

use function TempestPico\Support\Html\VT;

return VT(
    new Message('info', 'You may want to modify this component.'),
    new Message('warning', 'It make use of UnoCSS (node is needed)'),
    new Message('error', 'Something went **not** wrong… :D'),
);
