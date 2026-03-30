<?php

declare(strict_types=1);


namespace App;

use Tempest\Router\Get;
use Tempest\Router\StaticPage;
use Tempest\View\View;

use function Tempest\view;

final readonly class HomeController
{
    #[Get('/')]
    #[StaticPage]
    public function __invoke(): View
    {
        return view('./home.view.php');
    }
}
