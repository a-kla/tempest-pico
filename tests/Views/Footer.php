<?php

declare(strict_types=1);

namespace Tests\Views;

use AKl\Tempest_HtmlView\HtmlContent;
use AKl\Tempest_HtmlView\HtmlView;

use function AKl\Tempest_HtmlView\Html;

/** A Component that makes use of HtmlContent */
final class Footer extends HtmlView
{
    public function __construct(
        public ?string $content,
    ) {
        parent::__construct();
    }

    public function template(): HtmlContent
    {
        return Html('footer', [$this->content ?? '¢ …']);
    }
}
