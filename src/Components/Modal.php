<?php

declare(strict_types=1);

namespace TempestPico\Components;

use AKl\Tempest_HtmlView\HtmlContent;
use AKl\Tempest_HtmlView\HtmlView;
use Dom\Element;
use Dom\HTMLDocument;
use Dom\Node;

use function AKl\Tempest_HtmlView\content;
use function AKl\Tempest_HtmlView\Html;
use function Tempest\Support\Random\uuid;

#[Doc('Dialog (Modal)')]
final class Modal extends HtmlView
{
    /** any */
    public function __construct(
        public HtmlView|HtmlContent $content,
        public string|HtmlView|null $button = null, // null => open dialog
        public ?string $id = null,
        public string $closedby = 'any',
    ) {
        parent::__construct();
        $this->id ??= uuid();
    }

    public function template(): HtmlContent
    {
        if ($this->content instanceof HtmlView) {
            $this->content = content($this->content);
        }

        return content(
            new PhpDom(
                content(
                    match (true) {
                        is_null($this->button) => null,
                        default => Html('button', $this->button, [
                            'command' => 'show-modal',
                            'commandfor' => $this->id,
                        ]),
                    },
                    Html(
                        'dialog',
                        attributes: [
                            'open' => is_null($this->button),
                            'id' => $this->id,
                            'closedby' => $this->closedby,
                        ],
                    ),
                )
                    ->render()
                    ->toString(),
            )->onRender(function (HTMLDocument $htmlDocument): HTMLDocument {
                $modal = $htmlDocument->querySelector('dialog');
                assert($modal instanceof Element, 'dialog expected');

                $modal->innerHTML = match (true) {
                    $this->content instanceof HtmlView => $this->content
                        ->toHtml()
                        ->toString(),
                    default => $this->content->render()->toString(),
                };

                // Fix: [PicoCss] sets dialog to fill the screen => no space to click outside
                $modal->setAttribute(
                    'style',
                    'width: fit-content; height: fit-content; min-width: initial; min-height: initial;',
                );

                // Fix: [PicoCss] remove ugly margin
                /** @var null|(Node&Element) */
                $last = $modal->lastChild;
                if (is_null($last)) {
                    return $htmlDocument;
                }
                $last->setAttribute(
                    'style',
                    ($last->getAttribute('style') ?? '') . ';margin: 0;',
                );
                return $htmlDocument;
            }),
        );
    }
}
