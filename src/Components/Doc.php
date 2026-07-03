<?php

declare(strict_types=1);

/**
 * This Attribute is used to generate the documentation page of the components.
 */

namespace TempestPico\Components;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
final class Doc
{
    const array TAGS = [
        'TODO' => 'Not (fully) implemented',
        'Example' => '…',
        'Helper' => 'Helps you using Tempest Html-Views',
        'Unstyled' => 'Headless - Has no or minimal CSS',
        'Pico' => 'Requires Pico CSS (or something compatible)',
        'CSS' => 'Adds custom CSS',
        'JS' => 'JavaScript is needed',
    ];

    /**
     * @param null|key-of<self::TAGS>|key-of<self::TAGS>[] $tags
     **/
    public function __construct(
        public string $description,
        public string|array|null $tags = null,
        protected(set) ?string $customView = null,
    ) {}
}
