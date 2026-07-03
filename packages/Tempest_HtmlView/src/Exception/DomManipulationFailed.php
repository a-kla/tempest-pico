<?php

declare(strict_types=1);

namespace AKl\Tempest_HtmlView\Exception;

use Closure;
use Tempest\Core\HasContext;

final class DomManipulationFailed extends HtmlViewException implements
    HasContext
{
    public function __construct(
        \Throwable $previous,
        private readonly string $onFile,
        private readonly Closure $manipulator,
    ) {
        parent::__construct(
            "DOM manipulation failed while rendering {$onFile}.",
            previous: $previous,
        );
    }

    /** @return array<string, string> */
    public function context(): array
    {
        return [
            "onFile" => $this->onFile,
            "manipulator" => \var_export($this->manipulator, true),
        ];
    }
}
