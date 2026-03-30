<?php

declare(strict_types=1);

use League\CommonMark\MarkdownConverter;
use TempestPico\Components\Markdown;

use function Tempest\get;

/**
 * @var Markdown $this
 */

$markdown = get(MarkdownConverter::class);
$parsed = $markdown->convert($this->md)->getContent();
?>
{!! $parsed !!}
