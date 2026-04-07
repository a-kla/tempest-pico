<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Tempest\Support\Html\HtmlString;
use TempestPico\Support\Html\Exception\InvalidTag;
use TempestPico\Support\Html\Exception\VoidWithContent;
use TempestPico\Support\Html\HTMLBuilder;
use Tests\Views\Footer;
use Tests\Views\HeaderView;
use Tests\Views\Main;

use function Tempest\Support\arr;
use function Tempest\Support\str;
use function TempestPico\Support\Html;

/**
 * @internal
 */
// @mago-expect lint:too-many-methods
class HTMLBuilderTest extends TestCase
{
    #[Test]
    public function dosNotRenderEmptyNodes(): void
    {
        $html = (new HTMLBuilder())(null)('p')(element: null)(null)->render();

        $expected = '<p />';

        $this->assertSame(
            $expected,
            $html->toString(),
        );
    }

    #[Test]
    public function rendersWithContent(): void
    {
        $text = 'Text';
        $AHT = (new HTMLBuilder())('p', [$text]);
        $expected = "<p>{$text}</p>";

        $html = $AHT->render();
        print_r($html);

        $this->assertSame(
            $expected,
            $html->toString(),
        );
    }

    #[Test]
    public function rendersAppendedContent(): void
    {
        $text = 'Text';
        $AHT = (new HTMLBuilder())('p')->appendContent($text);
        $expected = "<p>{$text}</p>";

        $this->assertSame(
            $expected,
            $AHT->render()->toString(),
        );
    }

    #[Test]
    public function HelperFunktion(): void
    {
        $AHT = Html('br');

        $expected = '<br />';

        $this->assertSame(
            $expected,
            $AHT->render()->toString(),
        );
    }

    #[Test]
    public function canAppendContentMultitimes(): void
    {
        $AHT = Html('html')('body')('main', [html('h1', ['Headline'])]);

        $AHT = $AHT->appendContent(
            Html('hr'),
        );
        $AHT = $AHT->appendContent(
            Html('p', ['some Text']),
        );
        $AHT = $AHT->appendContent(
            Html('p', ['more Text']),
        );

        $expected = '<html><body><main><h1>Headline</h1><hr /><p>some Text</p><p>more Text</p></main></body></html>';

        $this->assertSame(
            $expected,
            $AHT->render()->toString(),
        );
    }

    #[Test]
    public function AllowsEmptyRootWithContent(): void
    {
        $html = new HTMLBuilder()->appendContent(
            new HtmlString('<hr />'),
            new HtmlString('<p>Test</p>'),
        );

        $expected = '<hr /><p>Test</p>';

        $this->assertSame(
            $expected,
            $html->render()->toString(),
        );
    }

    #[Test]
    public function AllowsEmptyRootWithContentUsingHelperFun(): void
    {
        $AHT = Html(
            element: null,
            content: [
                Html('hr'),
                Html('p', ['Test']),
            ],
        );

        $expected = '<hr /><p>Test</p>';

        $this->assertSame(
            $expected,
            $AHT->render()->toString(),
        );
    }

    #[Test]
    public function rendersTagsWithAttributesAndNoContent(): void
    {
        $attr = ['class' => 'fancyHr', 'style' => '--color("red")', 'data-test' => true];

        $AHT = Html('p', [], $attr);

        $expected = '<p class="fancyHr" style="--color("red")" data-test />';

        $this->assertSame(
            $expected,

            $AHT->render()->toString(),
        );
    }

    #[Test]
    public function ThrowsOnUnknownElement(): void
    {
        $this->expectException(InvalidTag::class);

        $AHT = Html('div')('p')('customTag');
        $expected = '<div><p><customTag /></p></div>';

        $this->assertSame(
            $expected,
            $AHT->render()->toString(),
        );
    }

    #[Test]
    public function AllowsCustomTag(): void
    {
        $AHT = Html('div')('p')->customTag('customTag');
        $expected = '<div><p><customTag /></p></div>';

        $this->assertSame(
            $expected,
            $AHT->render()->toString(),
        );
    }

    #[Test]
    public function ThrowsOnVoidTagWithContent(): void
    {
        $this->expectException(VoidWithContent::class);

        (new HTMLBuilder())('br')
            ->appendContent('This shall not work!');
    }

    // FIXME: using report() in Test throws Error: Call to a member function get() on null #[Test]
    public function attributesForNull(): void
    {
        // $this->expectException(AttributesForNull::class);

        $AHT = Html(element: null, attributes: ['just' => 'warn'], content: ['some']);
        $expected = 'some';

        $this->assertSame(
            $expected,
            $AHT->render()->toString(),
        );
    }

    #[Test]
    public function useComponentsAsContent(): void
    {
        $dangers = '<script … />';

        // TempestPico Components
        $footer = new Footer("Has {$dangers} content");
        $main = new Main('Get IDE support');

        $AHT = Html('body', [$main, $footer]);

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
            $AHT->render()->toString(),
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
            content: arr($items)->map(
                static fn ($item, $id) => Html('li', ["Item #{$id} {$item}"]),
            )->toArray(),
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
            Html('h1', [$text]),
            Html(element: 'p', content: [
                'Foo? ',
                Html('i', ['No. Bar!'], ['class' => 'bar']),
            ]),
            '- <= more Text… => -',
        ]);

        // Caution! Renders the main View NOW, all others on `render()`
        $main = Html('main', [$aside, $main()->unwrap('<main>', '</main>')]);

        $AHT = Html('body', [$header, $main, $footer]);

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
            $AHT->render()->toString(),
        );
    }
}
