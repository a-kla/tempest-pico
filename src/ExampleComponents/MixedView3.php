<?php

declare(strict_types=1);

namespace TempestPico\ExampleComponents;

use AKl\Tempest_HtmlView\HtmlContent;
use AKl\Tempest_HtmlView\HtmlView;
use Dom\HTMLDocument;
use TempestPico\Components\Doc;
use TempestPico\Components\Message;
use TempestPico\Components\PhpDom;

use function AKl\Tempest_HtmlView\content;
use function AKl\Tempest_HtmlView\Html;
use function Tempest\Support\Arr\map_iterable;
use function Tempest\view;

#[Doc('MixedView example as HtmlView', ['Example'])]
final class MixedView3 extends HtmlView
{
    public function __construct(
        public ?string $title = 'Title for the Html-View!',
    ) {
        parent::__construct();
    }

    public function template(): HtmlContent
    {
        return content(
            Html('hgroup', [
                Html('h3', 'No Pitfall:'),
                Html($this->title ? 'h4' : null, "{$this->title} ✔️"),
            ]),
            // Image it is from somewhere where you can't simply change the source. `file_get_contents('https:/…')`
            new PhpDom(
                <<<'HTML'
                    <hr>
                    <h4>Q: Can I mix your Components with my Tempest/Views?</h4>
                    <p>A: Yes! See:</p>
                    <hr>
                    HTML,
            )->onRender(
                // This is just an example to show how to manipulate it.
                static function (HTMLDocument $htmlDocument): HTMLDocument {
                    $h4 = $htmlDocument->querySelector('h4') ?? throw new \LogicException('h4 not found');
                    $h3 = $htmlDocument->createElement('h3');
                    $h3->textContent = $h4->textContent;
                    $h4->replaceWith($h3);
                    return $htmlDocument;
                },
            ),
            Html(
                // TODO: Component Form
                'form',
                attributes: [
                    'action' => 'javascript:void(0);',
                    'novalidate' => '',
                ],
                content: [
                    // Tempest View Component x-input
                    view(
                        'vendor/tempest/framework/packages/view/src/Components/x-input.view.php',
                        name: 'content2',
                        type: 'textarea',
                        label: 'Write your content',
                        placeholder: 'Unsupported and no hint for you 👿',
                    ),
                    Html(
                        'div',
                        attributes: ['role' => 'group'],
                        content: [
                            Html(
                                'label',
                                content: 'Find:',
                                attributes: ['for' => 'fl3-search'],
                            ),
                            Html(
                                'input',
                                attributes: [
                                    'name' => 'fl3-search',
                                    'type' => 'text',
                                    'placeholder' => 'Find',
                                ],
                            ),
                            Html(
                                'label',
                                content: 'In:',
                                attributes: ['for' => 'fl3-section'],
                            ),
                            Html(
                                'select',
                                content: map_iterable(
                                    ['Customers', 'Employees', 'Vendors'],
                                    static fn (
                                        string $value,
                                    ): HtmlContent => Html('option', $value, [
                                        'value' => $value,
                                    ]),
                                ),
                                attributes: [
                                    'id' => 'fl3-section',
                                    'name' => 'fl3-section',
                                ],
                            ),
                            Html(
                                'input',
                                attributes: [
                                    'type' => 'submit',
                                    'value' => 'Search',
                                ],
                            ),
                        ],
                    ),
                    Html(
                        'section',
                        [
                            Html(
                                'input',
                                attributes: [
                                    'type' => 'email',
                                    'id' => 'fl-email-ele',
                                    'placeholder' => 'Email',
                                    'aria-required' => 'true',
                                    'aria-labelledby' => 'fl-email',
                                ],
                            ),
                            Html(
                                'label',
                                content: 'Email',
                                attributes: [
                                    'for' => 'fl-email-ele',
                                    'id' => 'fl-email',
                                ],
                            ),
                        ],
                        ['role' => 'form'],
                    ),
                ],
            ),
            new Message('info', 'This is from the Html-View!'),
        );
    }
}
