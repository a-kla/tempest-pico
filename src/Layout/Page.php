<?php

declare(strict_types=1);

namespace TempestPico\Layout;

use AKl\Tempest_HtmlView\BaseView;
use AKl\Tempest_HtmlView\HtmlContent;
use AKl\Tempest_HtmlView\HtmlView;
use Tempest\Http\Request;
use Tempest\Router\RouteConfig;
use TempestPico\Components\MainNav;
use TempestPico\Page\Documentation;
use TempestPico\Page\Hello;

use function AKl\Tempest_HtmlView\content;
use function AKl\Tempest_HtmlView\Html;
use function AKl\Tempest_HtmlView\MD;
use function Tempest\get;
use function Tempest\Router\uri;
use function Tempest\Support\arr;
use function Tempest\Support\Str\ends_with;

/** Default Layout */
final class Page extends BaseView
{
    public MainNav $mainNav;
    public bool $isStatic;

    /**
     * @param array<string, string> $meta // ! No description|robots => robots = noindex,nofollow
     */
    public function __construct(
        string $title,
        public HtmlContent|HtmlView $main,
        array $meta = ['robots' => 'noindex,nofollow'],
    ) {
        parent::__construct(
            title: $title,
            meta: $meta,
            language: 'en', // TODO: config
        );

        $staticRoutes = arr(get(RouteConfig::class)->staticRoutes['GET'])
            ->filter(
                static fn ($data, string $uri): bool => ! ends_with($uri, '/'),
            )
            ->keys();
        $this->isStatic = $staticRoutes->includes(get(Request::class)->path); // FIXME: querys?

        $this->mainNav = new MainNav(
            [
                uri(Documentation::class) => 'Overview',
                uri([Documentation::class, 'components']) => 'Components',
                uri([Documentation::class, 'examples']) => 'Examples',
            ],
            burgerLinks: [
                uri([Documentation::class, 'readme']) => 'ReadMe',
                uri([Documentation::class, 'dom']) => 'HtmlDocument',
                uri([Documentation::class, 'assetManager']) => 'Asset-Manager',
                uri(Hello::class) => 'Minimal Example',
            ],
            linkPrefix: $this->assets->config->linkPrefix,
        );

        // `composer require yohn/picocss`
        $this->assets->css(
            'Layout/assets/vendor/PicoCSS-2.2.10/css/pico.indigo.min.css',
            'base',
        );
        $this->assets->css('Layout/assets/global.css', 'global');

        // not used atm
        // $this->assets->asset('Layout/assets/' . ($this->isStatic ? 'static' : 'dynamic') . '.css');
    }

    #[\Override]
    public function template(): HtmlContent
    {
        return content(
            Html(
                'header',
                Html('hgroup', [
                    Html('h1', 'Tempest-Pico'),
                    MD('Tempest v2 Starter Kit: Html View Builder'),
                ]),
                ['class' => 'container'],
            ),
            $this->mainNav,
            Html(
                'main',
                [Html('h1', $this->title), $this->main],
                ['class' => 'container'],
            ),
        );
    }
}
