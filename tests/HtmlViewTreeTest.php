<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use TempestPico\Support\Html\Exception\InvalidTag;
use TempestPico\Support\Html\Exception\VoidWithContent;
use TempestPico\Support\Html\HtmlViewTree;
use Tests\Views\Footer;
use Tests\Views\HeaderView;
use Tests\Views\Main;

use function Tempest\Support\Arr\map_iterable;
use function Tempest\Support\str;
use function TempestPico\Support\Html\Html;
use function TempestPico\Support\Html\VT;

/**
 * @internal
 */
// @mago-expect lint:too-many-methods
class HtmlViewTreeTest extends TestCase
{
    #[Test]
    public function rendersWithContent(): void
    {
        $text = 'Text';
        $VT = (new HtmlViewTree())('p', $text);
        $expected = "<p>{$text}</p>";

        $html = $VT->render();

        $this->assertSame(
            $expected,
            $html->toString(),
        );
    }

    #[Test]
    public function rendersAppendedContent(): void
    {
        $text = 'Text';
        $VT = (new HtmlViewTree())('p')->appendContent($text);
        $expected = "<p>{$text}</p>";

        $this->assertSame(
            $expected,
            $VT->render()->toString(),
        );
    }

    #[Test]
    public function HelperFunctionHtml(): void
    {
        $VT = Html('br');

        $expected = '<br />';

        $this->assertSame(
            $expected,
            $VT->render()->toString(),
        );
    }

    #[Test]
    public function generatesEscapedStrings(): void
    {
        $var = '<';
        $func = static fn () => VT(' />'); // Helper Function VT (View Tree)
        $VT = VT($var, 'br', $func(), Html('br'));

        $expected = '&lt;br /&gt;<br />';

        $this->assertSame(
            $expected,
            $VT->render()->toString(),
        );
    }

    #[Test]
    public function canAppendContentMultiTimes(): void
    {
        $VT = Html('html')('body')('main', [html('h1', 'Headline')]);

        $VT = $VT->appendContent(
            Html('hr'),
        );
        $VT = $VT->appendContent(
            Html('p', ['some Text']),
        );
        $VT = $VT->appendContent(
            Html('p', ['more Text']),
        );

        $expected = '<html><body><main><h1>Headline</h1><hr /><p>some Text</p><p>more Text</p></main></body></html>';

        $this->assertSame(
            $expected,
            $VT->render()->toString(),
        );
    }

    #[Test]
    public function AllowsEmptyNodes(): void
    {
        $node1 = null;
        $node2 = 'p';
        $html = (new HtmlViewTree())($node1)($node2)(element: null)(null)->render();

        $expected = '<p />';

        $this->assertSame(
            $expected,
            $html->toString(),
        );
    }

    #[Test]
    public function rendersTagsWithAttributesAndNoContent(): void
    {
        $attr = ['class' => 'fancyHr', 'style' => '--color("red")', 'data-test' => true];

        $VT = Html('p', [], $attr);

        $expected = '<p class="fancyHr" style="--color("red")" data-test />';

        $this->assertSame(
            $expected,

            $VT->render()->toString(),
        );
    }

    #[Test]
    public function ThrowsOnUnknownElement(): void
    {
        $this->expectException(InvalidTag::class);

        $VT = Html('div')('p')('customTag');
        $expected = '<div><p><customTag /></p></div>';

        $this->assertSame(
            $expected,
            $VT->render()->toString(),
        );
    }

    #[Test]
    public function AllowsCustomElements(): void
    {
        $VT = Html('div')('p')->customTag('customTag');
        $expected = '<div><p><customTag /></p></div>';

        $this->assertSame(
            $expected,
            $VT->render()->toString(),
        );
    }

    #[Test]
    public function ThrowsOnVoidElementWithContent(): void
    {
        $this->expectException(VoidWithContent::class);

        (new HtmlViewTree())('br')
            ->appendContent('This shall not work!');
    }

    // FIXME: using report() in Test throws Error: Call to a member function get() on null #[Test]
    public function attributesForNull(): void
    {
        // $this->expectException(AttributesForNull::class);

        $VT = Html(element: null, attributes: ['just' => 'warn'], content: 'some');
        $expected = 'some';

        $this->assertSame(
            $expected,
            $VT->render()->toString(),
        );
    }

    #[Test]
    public function worksWithComponentsAsContent(): void
    {
        $dangers = '<script … />';

        // TempestPico Components
        $footer = new Footer("Has {$dangers} content");
        $main = new Main('Get IDE support');

        $VT = Html('body', [$main, $footer]);

        $expected = str(<<<'HTML'
            <body>
                <main>
                    <h1>Get IDE support</h1>
                    <h2>Hello!</h2>
                    <p>Be <em>careful</em> if you use <code>new HtmlString()</code> <b>!</b></p>
                </main>
                <footer>Has &lt;script … /&gt; content</footer>
            </body>
            HTML)->replaceRegex('/>\s*</', '><');

        $this->assertSame(
            $expected->toString(),
            $VT->render()->toString(),
        );
    }

    //FIXME: #[Test]
    // My try throws Error because Tempest $container is null
    // May only needs a Testing setup?
    public function worksWithViewsAsContent(): void
    {
        // classic view.php
        $header = new HeaderView('New Home');

        $VT = Html('body', $header);

        $expected = str(<<<'HTML'
            <body>
                <header><a href="\">New Home</a></header>
            </body>
            HTML)->replaceRegex('/>\s*</', '><');

        $this->assertSame(
            $expected->toString(),
            $VT->render()->toString(),
        );
    }

    #[Test]
    public function ExampleList(): void
    {
        /** @var bool */
        $ordert = true;
        $items = [1 => 'Foo', 4 => 'Bar', 9 => 'Baz'];

        $html = Html(
            element: $ordert ? 'ol' : 'ul',
            attributes: ['class' => 'list'],
            content: map_iterable(
                $items,
                static fn ($item, $id) => Html('li', ["Item #{$id} {$item}"]),
            ),
        );

        $expected = '<ol class="list"><li>Item #1 Foo</li><li>Item #4 Bar</li><li>Item #9 Baz</li></ol>';

        $this->assertSame(
            $expected,

            $html->render()->toString(),
        );
    }

    #[Test]
    public function complexExample(): void
    {
        $tag = '<h1>'; // <= Text, not HTML
        $text = "In {$tag} tag…";

        // A Tempest View - UNIMPLEMENTED
        $header = new HeaderView('New Home');
        // A TempestPico Component
        $footer = new Footer(null);

        // Oh No! I need my aside in it!
        $main = new Main('A complex Example');

        $aside = Html('aside', [
            Html('h1', $text),
            Html(element: 'p', content: [
                'Foo? ',
                Html('i', 'No. Bar!', ['class' => 'bar']),
            ]),
            '- <= more Text… => -',
        ]);

        // Caution! Renders the main View NOW, all others on `render()`
        $main = Html('main', [$aside, $main->toHtml()->unwrap('<main>', '</main>')]);

        $VT = Html('body', [$header, $main, $footer]);

        $expected = str(<<<'HTML'
            <body>
                - FIXME: How to implement it? -
                <main>
                    <aside>
                        <h1>In &lt;h1&gt; tag…</h1>
                        <p>Foo? <i class="bar">No. Bar!</i></p>
                        - &lt;= more Text… =&gt; -
                    </aside>
                    <h1>A complex Example</h1>
                    <h2>Hello!</h2>
                    <p>Be <em>careful</em> if you use <code>new HtmlString()</code> <b>!</b></p>
                </main>
                <footer>¢ …</footer>
            </body>
            HTML)->replaceRegex('/([>-])\s*([<-])/', '\1\2');

        $this->assertSame(
            $expected->toString(),
            $VT->render()->toString(),
        );
    }
}
