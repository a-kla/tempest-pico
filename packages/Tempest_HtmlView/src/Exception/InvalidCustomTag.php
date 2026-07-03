<?php

declare(strict_types=1);

namespace AKl\Tempest_HtmlView\Exception;

use Tempest\Core\HasContext;

final class InvalidCustomTag extends HtmlViewException implements HasContext
{
    public function __construct(private readonly string $tag)
    {
        parent::__construct("Invalid custom HTML tag name: {$tag}");
    }

    /** @return array<string, string> */
    public function context(): array
    {
        return [
            "tag" => $this->tag,
        ];
    }
}
