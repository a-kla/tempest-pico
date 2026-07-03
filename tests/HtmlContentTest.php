<?php

declare(strict_types=1);

namespace Tests;

use AKl\Tempest_HtmlView\Exception\InvalidTag;
use AKl\Tempest_HtmlView\Exception\VoidWithContent;
use AKl\Tempest_HtmlView\HtmlContent;
use PHPUnit\Framework\Attributes\Test;
use Tempest\Framework\Testing\IntegrationTest;
use Tests\Views\Aside;
use Tests\Views\Footer;
use Tests\Views\HeaderView;
use Tests\Views\Main;

use function AKl\Tempest_HtmlView\content;
use function AKl\Tempest_HtmlView\Html;
use function Tempest\Support\Arr\map_iterable;
use function Tempest\Support\str;

/**
 * @internal
 */
// @mago-expect lint:too-many-methods
class HtmlContentTest extends IntegrationTest
{
    #[Test]
    public function rendersParagraphWithTextContent(): void
    {
        $text = 'Text';
        $htmlContent = (new HtmlContent())('p', $text);
        $expected = "<p>{$text}</p>";

        $html = $htmlContent->render();

        $this->assertSame($expected, $html->toString());
    }

    #[Test]
    public function rendersParagraphAfterAppendingText(): void
    {
        $text = 'Text';
        $htmlContent = (new HtmlContent())('p')->appendContent($text);
        $expected = "<p>{$text}</p>";

        $this->assertSame($expected, $htmlContent->render()->toString());
    }

    #[Test]
    public function helperRendersSelfClosingTag(): void
    {
        $htmlContent = Html('br');

        $expected = '<br>'; // Note: ''<br />'' is more clear, but HTMLDocument and modern tools omit the closing slash

        $this->assertSame($expected, $htmlContent->render()->toString());
    }

    #[Test]
    public function escapesMixedContentForSafeHtmlOutput(): void
    {
        $var = '<';
        $func = static fn (): HtmlContent => content(' />');

        $htmlContent = content($var, 'br', $func(), Html('br'));

        $expected = '&lt;br /&gt;<br>';

        $this->assertSame($expected, $htmlContent->render()->toString());
    }

    #[Test]
    public function appendsMultipleChildrenInOrder(): void
    {
        $htmlContent = Html('html')('body')('main', [html('h1', 'Headline')]);

        $htmlContent = $htmlContent->appendContent(Html('hr'));
        $htmlContent = $htmlContent->appendContent(Html('p', ['some Text']));
        $htmlContent = $htmlContent->appendContent(Html('p', ['more Text']));

        $expected = '<html><body><main><h1>Headline</h1><hr><p>some Text</p><p>more Text</p></main></body></html>';

        $this->assertSame($expected, $htmlContent->render()->toString());
    }

    #[Test]
    public function rendersEmptyParagraphWhenGivenNullContent(): void
    {
        $node1 = null;
        $node2 = 'p';
        $html = (new HtmlContent())($node1)($node2)(element: null)(
            null,
        )->render();

        $expected = '<p></p>';

        $this->assertSame($expected, $html->toString());
    }

    #[Test]
    public function rendersAttributesOnElementWithoutChildren(): void
    {
        $attr = [
            'class' => 'fancyHr',
            'style' => '--color("red")',
            'data-test' => true,
        ];

        $htmlContent = Html('p', [], $attr);

        $expected = '<p class="fancyHr" style="--color("red")" data-test></p>';

        $this->assertSame(
            $expected,

            $htmlContent->render()->toString(),
        );
    }

    #[Test]
    public function throwsWhenTagIsNotAValidHtmlTag(): void
    {
        $this->expectException(InvalidTag::class);

        $htmlContent = Html('div')('p')('customTag');
        $expected = '<div><p><customTag /></p></div>';

        $this->assertSame($expected, $htmlContent->render()->toString());
    }

    #[Test]
    public function allowsValidCustomElementNames(): void
    {
        $htmlContent = Html('div')('p')->customTag('custom-tag', '<Text>');
        $expected = '<div><p><custom-tag>&lt;Text&gt;</custom-tag></p></div>';

        $this->assertSame($expected, $htmlContent->render()->toString());
    }

    #[Test]
    public function RendersCustomElementsNotSelfClosing(): void
    {
        $htmlContent = Html('div')('p')->customTag('custom-tag');
        $expected = '<div><p><custom-tag></custom-tag></p></div>';

        $this->assertSame($expected, $htmlContent->render()->toString());
    }

    #[Test]
    public function throwsWhenAppendingContentToVoidElement(): void
    {
        $this->expectException(VoidWithContent::class);

        (new HtmlContent())('br')->appendContent('This shall not work!');
    }

    #[Test]
    public function attributesForNull(): void
    {
        // $this->expectException(AttributesForNull::class);

        $htmlContent = Html(
            element: null,
            content: 'some',
            attributes: ['just' => 'warn'],
        );
        $expected = 'some';

        $this->assertSame($expected, $htmlContent->render()->toString());
    }

    #[Test]
    public function rendersComponentsAsChildren(): void
    {
        $danger = '<script … />';

        $footer = new Footer("Has {$danger} content");
        $main = new Main('Get IDE support');

        $htmlContent = Html('body', [$main, $footer]);
        $rendered = $htmlContent->render()->toString();

        $this->assertStringContainsString('<body>', $rendered);
        $this->assertStringContainsString('<h1>Get IDE support</h1>', $rendered);
        $this->assertStringContainsString('<footer>Has &lt;script … /&gt; content</footer>', $rendered);
    }

    #[Test]
    public function rendersViewsAsChildren(): void
    {
        // classic view.php
        $headerView = new HeaderView('New Home');

        $htmlContent = Html('body', $headerView);

        /*
         * Note: HeaderView does not proper use the parameter, so it renders an empty <a> tag.
         * This is to show why I dislike tempest's own Views.
         * (And all other templating engines that don't have proper support in many IDEs).
         */
        $expected = str(
            <<<'HTML'
                <body>
                    <header><a href="\"></a></header>
                </body>
                HTML,
        )
            ->replaceRegex("/>\s*</", '><');

        $this->assertSame($expected->toString(), $htmlContent->render()->toString());
    }

    #[Test]
    public function buildsListFromMappedItems(): void
    {
        /** @var bool */
        $ordert = true;
        $items = [1 => 'Foo', 4 => 'Bar', 9 => 'Baz'];

        $html = Html(
            element: $ordert ? 'ol' : 'ul',
            content: map_iterable(
                $items,
                static fn ($item, $id): HtmlContent => Html('li', [
                    "Item #{$id} {$item}",
                ]),
            ),
            attributes: ['class' => 'list'],
        );

        $expected = '<ol class="list"><li>Item #1 Foo</li><li>Item #4 Bar</li><li>Item #9 Baz</li></ol>';

        $this->assertSame(
            $expected,

            $html->render()->toString(),
        );
    }

    // Smoke Test
    #[Test]
    public function rendersNestedHtmlTreeExample(): void
    {
        $tag = '<h1>';
        $text = "In {$tag} tag…";

        $headerView = new HeaderView('Misspelled Title');
        $footer = new Footer(null);
        $main = new Main('A complex Example');
        $aside = new Aside($text);

        $main = Html('main', [
            $aside,
            $main->toHtml()->unwrap('<main>', '</main>'),
        ]);

        $htmlContent = Html('body', [$headerView, $main, $footer]);
        $rendered = $htmlContent->render()->toString();

        $this->assertStringContainsString('<body>', $rendered);
        $this->assertStringContainsString('<header>', $rendered);
        $this->assertStringContainsString('In &lt;h1&gt; tag…', $rendered);
        $this->assertStringContainsString('<h1>A complex Example</h1>', $rendered);
        $this->assertStringContainsString('<h2>Main Component</h2>', $rendered);
        $this->assertStringContainsString('<footer>', $rendered);
    }
}
