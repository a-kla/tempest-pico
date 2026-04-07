<?php

declare(strict_types=1);

namespace TempestPico\Support\Html\Exception;

use Tempest\Core\HasContext;

final class VoidWithContent extends HtmlBuilderException implements HasContext
{
    public function __construct(
        private string $tag,
    ) {
        parent::__construct("The HTML tag {$tag} can not have content.");
    }

    /** @return array<string, string> */
    public function context(): array
    {
        return [
            'tag' => $this->tag,
        ];
    }
}
