<?php

declare(strict_types=1);

namespace TempestPico\Support\Html\Exception;

use Tempest\Core\HasContext;

final class AttributesForNull extends HtmlBuilderException implements HasContext
{
    public function __construct(
        private string $attributes,
    ) {
        parent::__construct("It don`t make sense to add {$attributes} to a Root Node (null Element).");
    }

    /** @return array<string, string> */
    public function context(): array
    {
        return [
            'attributes' => $this->attributes,
        ];
    }
}
