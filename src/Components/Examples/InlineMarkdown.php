<?php

declare(strict_types=1);

namespace TempestPico\Components\Examples;

use function TempestPico\Support\Html\IMD;
use function TempestPico\Support\Html\MD;
use function TempestPico\Support\Html\VT;

return VT(
    IMD('Some *Text*'),
    MD('Some *Text* inside `<p />`'),

    MD('### Headline'),
    IMD('### no Headline allowed'),
);
