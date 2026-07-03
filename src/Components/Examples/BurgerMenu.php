<?php

declare(strict_types=1);

namespace TempestPico\Components\Examples;

use TempestPico\Components\BurgerMenu;

use function Tempest\Router\uri;

return new BurgerMenu([
    // use `uri(::class)` instead
    uri('/') => 'Home',
    uri('/about') => 'About',
    uri('/contact') => 'Contact',
]);
