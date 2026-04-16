<?php

declare(strict_types=1);

namespace TempestPico\Layout;

use Tempest\Http\Request;
use Tempest\Router\RouteConfig;
use TempestPico\Components\Component;
use TempestPico\Support\Html\HtmlViewTree;

use function Tempest\get;
use function Tempest\Support\arr;
use function Tempest\Support\Str\ends_with;
use function TempestPico\Support\Html\Html;
use function TempestPico\Support\Html\MD;
use function TempestPico\Support\Html\VT;

/** Default Layout */
final class Page implements Component
{
    use IsLayout;

    public MainNav $mainNav;
    public bool $isStatic; // TODO: use routes.json

    public function __construct(
        public string $title,
        public HtmlViewTree $main,
    ) {
        $this->setPaths();

        $staticRoutes = arr(get(RouteConfig::class)->staticRoutes['GET'])
            ->filter(static fn ($data, string $uri) => ! ends_with($uri, '/'))
            ->keys();
        $this->isStatic = $staticRoutes->includes(get(Request::class)->path); // FIXME: querys?

        $this->mainNav = new MainNav([
            'doc/' => 'Overview',
            'doc/components' => 'Components',
            'doc/readme' => 'ReadMe',
            // '' => 'Default Tempest Example',
            // 'example' => 'My Example',
            // 'tables' => 'Table Example',
        ]);
    }

    public function getViewTree(): HtmlViewTree
    {
        return VT(
            Html(
                'header',
                Html('hgroup', [
                    Html('h1', 'Tempest-Pico'),
                    MD('Tempest v2 Starter Kit: View Builder + Pico CSS (+ UnoCSS if you need more)'),
                    $this->mainNav,
                ]),
                ['class' => 'container'],
            ),
            Html(
                'main',
                [
                    Html('h1', $this->title),
                    $this->main,
                ],
                ['class' => 'container'],
            ),
        );
    }
}
