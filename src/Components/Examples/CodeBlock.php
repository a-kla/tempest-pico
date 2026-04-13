<?php

declare(strict_types=1);

namespace TempestPico\Components\Examples;

use TempestPico\Components\CodeBlock;

use function TempestPico\Support\Html\Html;
use function TempestPico\Support\Html\VT;

$codeWrong = <<<'PHP'
    // Pitfall: appending to the wrong Element
    Html('main')
        ('h1', 'Hello World')
        ->appendContent()('p', 'This is a paragraph');
    PHP;

$htmlWrong = Html('main')('h1', 'Hello World')
    ->appendContent()('p', 'This is a paragraph');

$codeUgly = <<<'PHP'
    // ugly:
    Html('main', Html('h1', 'Hello World'))
        ->appendContent()('p', 'This is a paragraph'); 
    PHP;

$htmlUgly = Html('main', Html('h1', 'Hello World'))
    ->appendContent()('p', 'This is a paragraph');

$php = <<<'PHP'
    // best: avoid appendContent()
    Html('main', [
        Html('h1', 'Hello World'),
        Html('p', 'This is a paragraph'),
    ]);
    PHP;

$php2 = <<<'PHP'
    // If you really want `p` inside `h1` simply use
    Html('main')('h1', 'Hello World')('p', 'This is a paragraph'); 
    PHP;

return VT(
    new CodeBlock($php, 'php'),
    Html('hr'),
    new CodeBlock($codeUgly, 'php'),
    Html('hr'),
    new CodeBlock($htmlUgly->render()->toString(), 'html'),
    Html('hr'),
    new CodeBlock($codeWrong, 'sql'), // sql is semantic wrong but looks OK
    Html('hr'),
    new CodeBlock($htmlWrong->render()->toString(), 'html'),
    Html('hr'),
    new CodeBlock($php2, 'php'),
);
