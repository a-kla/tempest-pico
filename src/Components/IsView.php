<?php

declare(strict_types=1);

namespace TempestPico\Components;

use Tempest\View\Renderers\TempestViewRenderer;

use function Tempest\Support\path;
use function Tempest\Support\Path\normalize;

/** @phpstan-require-implements \Tempest\View\View */
trait IsView
{
    public string $path;

    public ?string $relativeRootPath = null;

    public function __invoke(): string
    {
        return \Tempest\get(TempestViewRenderer::class)->render($this);
    }

    public function __construct()
    {
        $this->setPaths();
    }

    public function setPaths()
    {
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        $path = str_ends_with(normalize($trace[0]['file']), 'view/src/functions.php')
            ? path($trace[1]['file'])
            : path($trace[0]['file']);

        $this->relativeRootPath = $path->dirname()->toString();
        $this->path = $path->filename()->stripEnd('.php') . '.view.php';
    }

    // ## required by tempest

    public array $data = [];

    public function get(string $key): mixed
    {
        return $this->{$key} ?? $this->data[$key] ?? null;
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $this->data) || property_exists($this, $key);
    }

    public function data(mixed ...$params): self
    {
        $this->data = [...$this->data, ...$params];

        return $this;
    }
}
