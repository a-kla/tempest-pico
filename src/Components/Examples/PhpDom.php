<?php

declare(strict_types=1);

namespace TempestPico\Components\Examples;

use Tempest\Support\Html\HtmlString;
use TempestPico\Components\CodeBlock;
use TempestPico\Components\PhpDom;
use TempestPico\Page\Documentation;

use function AKl\Tempest_HtmlView\content;
use function AKl\Tempest_HtmlView\Dom;
use function AKl\Tempest_HtmlView\Html;
use function AKl\Tempest_HtmlView\IMD;
use function Tempest\Router\uri;

$code = <<<'HTML_WRAP'
<h2>Why to use <code>Dom()</code>, not HtmlString?</h1> <!-- h1 or h2? -->
</p><!-- no opening <p> -->
    If the HTML is invalid, <em>Dom\HtmlDocument</em> will try to fix it like a Browser dose.<br>
    You can disable the fixing and throw an error instead:
    <code>new PhpDom($code, fixHtml: false)</code>
</p>
<p><em>HtmlString<em><!-- should be a closing em --> only prevents double escaping. <!-- missing /p -->
<p>The result looks identical to the user but search engines love well formed HTML.</p>
HTML_WRAP;

return content(
    Dom($code), // shortcut
    Html(
        'a',
        IMD('More about `PhpDom` and `$component->onRender()`'),
        attributes: ['href' => uri([Documentation::class, 'dom'])],
    ), // TODO: Link component
    Html('h3', 'Same as HtmlString: '),
    new HtmlString($code),

    Html('hr'),
    Html('h4', 'HtmlString: '),
    new CodeBlock(
        new HtmlString($code)->toString(),
        'html',
    ),

    Html('hr'),
    Html('h4', 'PhpDom: '),
    new CodeBlock(
        new PhpDom($code)->toHtml()->toString(),
        'html',
    ),
);
