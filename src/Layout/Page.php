<?php

namespace TempestPico\Layout;

use TempestPico\Components\IsView;
use Tempest\View\View;

/** Default Layout */
final class Page implements View
{
    use IsView;
    
    /**
     * @param View $main
     **/
    public function __construct(
        public string $title,
        public View $main,
        public bool $isStatic = false // TODO: find out how to autodetect `static:generate` is running
        ) {
        $this->setPaths();
    }
    
}
