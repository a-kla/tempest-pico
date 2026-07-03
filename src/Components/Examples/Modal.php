<?php

declare(strict_types=1);

namespace TempestPico\Components\Examples;

use TempestPico\Components\Card;
use TempestPico\Components\Modal;
use TempestPico\Components\PhpDom;

use function AKl\Tempest_HtmlView\content;
use function AKl\Tempest_HtmlView\IMD;
use function AKl\Tempest_HtmlView\MD;

return content(
    new Modal(
        new PhpDom(
            <<<'HTML'
                <p>Hello World!</p>
                HTML,
        ),
        'open #1',
    ),
    new Modal(
        new Card(
            MD(
                'Modal without ID are open am maybe invisible if inside a Detail (Accordion)',
            ),
            IMD('Info'),
            new PhpDom(
                <<<'HTML'
                    <form method="dialog">
                        <button autofocus>❌</button>
                    </form>
                    HTML,
            ),
        ),
        'show Info',
    ),
);
