<?php

declare(strict_types=1);

namespace TempestPico\Layout;

use Tempest\Support\Uri\Uri;
use TempestPico\Components\Component;
use TempestPico\Support\Html\HtmlViewTree;

use function Tempest\env;
use function Tempest\Support\Arr\map_iterable;
use function Tempest\Support\Str\ensure_ends_with;
use function TempestPico\Support\Html\Html;

final class MainNav implements Component
{
    use IsLayout;

    /**
     * @param array<string, string> $links
     **/
    public function __construct(
        public array $links,
    ) {
        $this->setPaths();
    }

    public function getViewTree(): HtmlViewTree
    {
        $baseUrl = ensure_ends_with(Uri::from(env('LINK_TO', '/')), '/');

        return Html('nav', [
            Html('ul', map_iterable(
                $this->links,
                static fn ($text, $url) => Html('li', [
                    Html('a', $text, ['href' => $baseUrl . $url]),
                ]),
            )),
        ]);
    }
}
