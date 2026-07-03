<?php

declare(strict_types=1);

namespace AKl\Tempest_HtmlView\Exception;

use Tempest\Core\HasContext;

final class HtmlToDomFailed extends HtmlViewException implements HasContext
{
    public function __construct(
        \Throwable $previous,
        private readonly string $html,
    ) {
        parent::__construct(
            "HTML 2 DOM failed.\nInput HTML:\n{$html}",
            previous: $previous,
        );
    }

    /** @return array<string, string> */
    public function context(): array
    {
        return [
            "html" => $this->html,
        ];
    }
}
