<?php

declare(strict_types=1);

namespace TempestPico\Layout;

use Tempest\View\View;

/** Default Layout */
final class Page implements View
{
    use IsView;

    public MainNav $mainNav;

    /**
     * @param View $main
     **/
    public function __construct(
        public string $title,
        public View $main,
        public bool $isStatic = false, // TODO: use routes.json
    ) {
        $this->setPaths();

        $this->mainNav = new MainNav([
            'doc/' => 'Overview',
            'doc/components' => 'Components',
            'doc/readme' => 'ReadMe',
            // '' => 'Default Tempest Example',
            // 'example' => 'My Example',
            // 'tables' => 'Table Example',
        ]);
    }
}
