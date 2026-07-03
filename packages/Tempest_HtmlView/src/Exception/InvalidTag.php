<?php

declare(strict_types=1);

namespace AKl\Tempest_HtmlView\Exception;

use Tempest\Core\HasContext;

final class InvalidTag extends HtmlViewException implements HasContext
{
    public function __construct(private readonly string $tag)
    {
        parent::__construct("Unknown HTML tag: {$tag}.");
    }

    /** @return array<string, string> */
    public function context(): array
    {
        return [
            "tag" => $this->tag,
        ];
    }
}
