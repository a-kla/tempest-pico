<?php

declare(strict_types=1);

namespace TempestPico\Page;

use Tempest\Router\Get;
use Tempest\Router\Prefix;
use Tempest\Router\StaticPage;
use TempestPico\Components\Card;
use TempestPico\Components\Doc;
use TempestPico\Components\Markdown;
use TempestPico\Components\Stack;
use TempestPico\Layout\Page;

use function Tempest\reflect;
use function Tempest\Support\arr;
use function Tempest\Support\Arr\implode;

final readonly class Components
{
    #[StaticPage]
    #[Prefix('/doc')]
    #[Get('/component')]
    public function __invoke(): Page
    {
        $cards = arr(Doc::COMPONENTS)
            ->sort()
            ->map(
                static function ($component) {
                    // TODO: use a View
                    $asHashTags = static fn (null|string|array $tags) => $tags === null
                        ? null
                        : new Markdown("\n\n #" . (gettype($tags) === 'string' ? $tags : implode($tags, ' #')));

                    $ref = reflect($component);
                    $doc = $ref->getAttribute(Doc::class);

                    return new Card(
                        // @phpstan-ignore method.notFound
                        header: new Markdown("# {$ref->getShortName()}"),
                        content: new Markdown($doc === null ? 'No description available.' : $doc->description),
                        footer: $doc === null ? null : $asHashTags($doc->tags),
                    );
                },
            );

        return new Page(
            title: 'Components Overview',
            isStatic: true,
            main: new Stack(new Markdown('A List of the components I have created so far.'), ...$cards),
        );
    }
}
