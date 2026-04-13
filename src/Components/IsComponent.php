<?php

declare(strict_types=1);

namespace TempestPico\Components;

use Deprecated;
use Tempest\Support\Html\HtmlString;

use function Tempest\Support\path;
use function Tempest\Support\Path\normalize;

/** @phpstan-require-implements Component */
trait IsComponent
{
    public string $path;

    public ?string $relativeRootPath = null;

    public function __construct()
    {
        $this->setPaths();
    }

    #[Deprecated('use `->toHtml()` for clarity')]
    public function __invoke(): HtmlString
    {
        return $this->toHtml();
    }

    public function toHtml(): HtmlString
    {
        return $this->getViewTree()->render();
    }

    public function setPaths(): void
    {
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        /**
         * @mago-expect analysis:mixed-array-access, mixed-argument
         * @phpstan-ignore offsetAccess.notFound
         */
        $path = str_ends_with(normalize($trace[0]['file']), 'view/src/functions.php')
            /**
             * @mago-expect analysis:mixed-array-access, mixed-argument
             * @phpstan-ignore offsetAccess.notFound
             */
            ? path($trace[1]['file'])
            /**
             * @mago-expect analysis:mixed-array-access, mixed-argument
             * @phpstan-ignore offsetAccess.notFound
             */
            : path($trace[0]['file']);

        $this->relativeRootPath = $path->dirname()->toString();
        $this->path = $path->filename()->stripEnd('.php') . '.view.php';
    }

    // ### required by tempest ###

    /** @var mixed[] $data */
    public array $data = [];

    public function get(string $key): mixed
    {
        // @mago-expect analysis:string-member-selector
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
