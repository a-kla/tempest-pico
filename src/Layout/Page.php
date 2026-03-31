<?php

declare(strict_types=1);

namespace TempestPico\Layout;

use Tempest\View\View;
use TempestPico\Components\IsView;

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
        public bool $isStatic = false, // TODO: find out how to autodetect `static:generate` is running
    ) {
        $this->setPaths();

        $this->mainNav = new MainNav([
            '' => 'Default Tempest Example',
            'example' => 'My Example',
            'doc/component' => 'Components',
            'tables' => 'Table Example',
        ]);
    }
}
