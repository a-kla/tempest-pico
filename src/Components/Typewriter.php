<?php

declare(strict_types=1);

namespace TempestPico\Components;

use AKl\Tempest_HtmlView\HtmlContent;
use AKl\Tempest_HtmlView\HtmlView;

use function AKl\Tempest_HtmlView\content;
use function AKl\Tempest_HtmlView\cssVar;
use function AKl\Tempest_HtmlView\Html;

#[Doc('CSS only typing effect', ['Example', 'Custom'])]
final class Typewriter extends HtmlView
{
    public function __construct(
        private(set) string $text,
        private(set) ?string $color = null,
        private(set) HtmlView|string|null $before = null,
        private(set) HtmlView|string|null $after = null,
    ) {
        parent::__construct();
    }

    public function template(): HtmlContent
    {
        return content(
            Html(
                'p',
                content(
                    $this->before,
                    Html(
                        'span',
                        attributes: [
                            'class' => 'type',
                            'style' => cssVar(
                                n: \mb_strlen($this->text),
                            ),
                        ],
                        content: $this->text,
                    ),
                    $this->after,
                ),
                attributes: [
                    'style' => cssVar(
                        color: $this->color,
                    ),
                ],
            ),
        );
    }
}
