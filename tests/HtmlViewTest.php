<?php

declare(strict_types=1);

namespace Tests;

use Dom\HTMLDocument;
use Dom\HTMLElement;
use PHPUnit\Framework\Attributes\Test;
use Tempest\Framework\Testing\IntegrationTest;
use Tests\Views\Aside;
use Tests\Views\Main;

use function AKl\Tempest_HtmlView\Dom;
use function Tempest\Support\Arr\each;
use function Tempest\Support\str;

/**
 * @internal
 */
class HtmlViewTest extends IntegrationTest
{
    #[Test]
    public function appliesDomManipulationsAfterRendering(): void
    {
        $aside = new Aside('Aside');

        $aside->onRender(
            static function (HTMLDocument $htmlDocument): HTMLDocument {
                each(
                    $htmlDocument->querySelectorAll('p')->getIterator(),
                    static function (HTMLElement $htmlElement): void {
                        $htmlElement->classList->add('outline');
                    },
                );
                return $htmlDocument;
            },
        );

        /* let us assume we can not simply refactor a Component and need to inject something */
        $main = new Main('A Example')
            // Yes, in RL it makes much more sense to menge both
            // But we want to test the executionOrder
            ->onRender(
                // add a class to h1 in aside
                static function (HTMLDocument $htmlDocument): HTMLDocument {
                    $htmlDocument->querySelector('aside > h1')?->classList->add('injected');
                    return $htmlDocument;
                },
                executionOrder: 7, // execute later then other manipulations
            )
            ->onRender(
                // inject the aside at the beginning of main
                static function (HTMLDocument $htmlDocument) use ($aside): HTMLDocument {
                    $node = $htmlDocument->importNode(Dom($aside->toHtml()->toString())->body, true);

                    $htmlDocument->querySelector('main')?->prepend(...$node->childNodes);

                    return $htmlDocument;
                },
            );

        $expected = str(
            <<<'HTML'
                    <main>
                        <aside>
                            <h1 class="injected">Aside</h1>
                            <p class="outline">Foo? <i class="bar">No. Bar!</i></p>
                            <hr>
                            - &lt;= more Text… =&gt; -
                        </aside>
                        <h1>A Example</h1>
                        <h2>Main Component</h2>
                        <p>Be <em>careful</em> if you use <code>new HtmlString()</code>&lt;b&gt;!&lt;/b&gt;</p>
                        <p>-&lt;script&gt;alert(„Hello XSS!“);&lt;/script&gt;-</p>
                    </main>
                HTML,
        )
            ->trim()
            ->replaceRegex('/([>-])\s*([<-])/', '\1\2');

        $this->assertSame(
            $expected->toString(),
            $main->toHtml()->toString(),
        );
    }
}
