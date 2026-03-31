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
use function Tempest\Support\Arr\each;
use function Tempest\Support\Arr\implode;

final readonly class Components
{
    #[StaticPage]
    #[Prefix('/doc')]
    #[Get('/component')]
    public function __invoke(): Page
    {
        $content = [
            new Markdown('A List of the components I have created so far.'),
        ];

        each(Doc::COMPONENTS, function (string $component, int $_) use (&$content) {
            $ref = reflect($component);
            $doc = $ref->getAttribute(Doc::class);
            $hashTag = fn (null|string|array $tags) => $tags === null ? '' : "\n\n #" . (gettype($tags) === 'string' ? $tags : implode($tags, ' #'));

            $content[] = new Card(
                header: new Markdown("# {$ref->getShortName()}"),
                content: new Markdown($doc === null ? 'No description available.' : $doc->description),
                footer: $doc === null ? null : new Markdown($hashTag($doc->tags)),
            );
        });

        return new Page(
            title: 'Components Overview',
            isStatic: true,
            main: new Stack($content),
        );
    }
}
