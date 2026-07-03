<?php

declare(strict_types=1);

namespace TempestPico\Page\Exception;

use Tempest\Core\HasContext;
use Tempest\Support\Path\Path;
use Throwable;

final class InvalidExample extends \Exception implements HasContext
{
    public function __construct(
        private readonly Path $path,
        private readonly Throwable $previous,
    ) {
        parent::__construct(
            "Error in Example {$path->basename()->toString()}",
            previous: $previous,
        );
    }

    /** @return array<string, string> */
    public function context(): array
    {
        return [
            "path" => $this->path->toString(),
            "previous" => $this->previous->__toString(),
        ];
    }
}
