<?php

declare(strict_types=1);

namespace AKl\Tempest_HtmlView\Exception;

use Tempest\Core\HasContext;

final class AttributesForNull extends HtmlViewException implements HasContext
{
    public function __construct(private readonly string $attributes)
    {
        parent::__construct(
            "Container Node (null Element) got Attributes {$attributes}.",
        );
    }

    /** @return array<string, string> */
    public function context(): array
    {
        return [
            "attributes" => $this->attributes,
        ];
    }
}
