<?php

declare(strict_types=1);

namespace TempestPico\Page;

use Tempest\Router\Get;
use Tempest\Router\StaticPage;
use TempestPico\ExampleComponents\HelloWorld;
use TempestPico\Layout\Page;

final readonly class Hello
{
    #[StaticPage]
    #[Get('/hello')]
    public function __invoke(): Page
    {
        return new Page('Minimal Example Page', main: new HelloWorld());
    }
}
