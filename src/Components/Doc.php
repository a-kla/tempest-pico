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
    /** @var class-string[] COMPONENTS */
    const array COMPONENTS = [
        Card::class,
        Markdown::class,
        Messages::class,
        Stack::class,
        Table::class,
    ];

    const TAGS = [
        'Pico' => 'This Component use only HTML + Pico CSS',
        'Helper' => 'Helps you using Tempest Views',
        'Custom' => 'This Component is custom made for specific use cases',
        'TODO' => 'This Component is not implemented yet',
    ];

    /**
     * @param null|key-of<self::TAGS>|key-of<self::TAGS>[] $tags
     **/
    public function __construct(
        public string $description,
        public null|string|array $tags = null,
    ) {}
}
