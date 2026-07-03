<?php

declare(strict_types=1);

namespace TempestPico\Components\Examples;

use function AKl\Tempest_HtmlView\content;
use function AKl\Tempest_HtmlView\IMD;
use function AKl\Tempest_HtmlView\MD;

return content(
    // IDM() vs MD()
    MD('Some *Text* (inside `<p />`)'),
    IMD('Some *Text*'),

    MD('### Headline'),
    IMD('### no Headline allowed'),
    // MD(path(__DIR__, 'file.md')),
);
