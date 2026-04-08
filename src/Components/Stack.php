<?php

declare(strict_types=1);

namespace TempestPico\Components;

use Deprecated;
use TempestPico\Support\Html\HtmlViewTree;

use function TempestPico\Support\Html;

#[Doc("Deprecated: Use Helper fun `VT();`!\n\nStack multiple components on top of each other to put it in a single View \"slot\".", ['Helper'])]
final class Stack implements Component
{
    use IsComponent;

    /**
     * @var array<array-key, Component> $components
     **/
    public array $components;

    #[Deprecated('Use `Html(null, $components);`')]
    public function __construct(
        Component ...$components,
    ) {
        $this->components = $components;
        $this->setPaths();
    }

    public function getViewTree(): HtmlViewTree
    {
        return Html(null, $this->components);
    }

    /*
     * public function toHtml(): HtmlString
     * {
     * $html = $this->components->map(
     * static fn (Component $component) => $component->toHtml(),
     * )->implode('');
     *
     * return new HtmlString($html);
     * }
     */
}
