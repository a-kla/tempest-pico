<?php

declare(strict_types=1);

namespace TempestPico\Components;

use AKl\Tempest_HtmlView\HtmlContent;
use AKl\Tempest_HtmlView\HtmlView;
use Tempest\Reflection\ClassReflector;
use Tempest\Support\Path\Path;

use function AKl\Tempest_HtmlView\content;
use function AKl\Tempest_HtmlView\Html;
use function AKl\Tempest_HtmlView\MD;
use function Tempest\reflect;
use function Tempest\Support\Filesystem\read_file;
use function Tempest\Support\path;
use function Tempest\Support\Str\class_basename;

#[
    Doc(
        'Renders a documentation card for a component, including examples and source code.',
    ),
]
final class ComponentDocumentation extends HtmlView
{
    public function __construct(
        private readonly string $component,
    ) {
        parent::__construct();
    }

    public function template(): HtmlContent
    {
        /** @var ClassReflector<HtmlView> */
        $ref = reflect($this->component);
        $file = $ref->getFileName();

        $content = content();
        $docFile = $this->resolveDocFile($file);

        if ($docFile->exists()) {
            $content->appendContent(MD($docFile));
        }

        $exampleFile = $this->resolveExampleFile($file);

        if ($exampleFile->exists()) {
            $content->appendContent(
                $this->templateExampleAccordion($exampleFile),
            );
        } else {
            $content->appendContent(
                new Message('info', 'There is no example available yet.'),
            );
        }

        $customView = $ref->getAttribute(Doc::class)->customView ?? false;
        $viewFile = $this->resolveViewFile($file, $customView);

        $content->appendContent(
            new Accordion(
                class_basename($this->component),
                [
                    'Code: ' . path($file)->basename()->toString() => content(
                        new CodeBlock(path($file), 'php'),
                        class_basename($this->component) === 'HtmlContent'
                            ? null
                            : new CodeBlock($viewFile, 'php'),
                    ),
                ],
                Accordion::Options('h3'),
            ),
        );

        return content($this->templateCard($ref, $content));
    }

    private function templateCard(
        ClassReflector $classReflector,
        HtmlContent $htmlContent,
    ): Card {
        $doc = $classReflector->getAttribute(Doc::class);

        return new Card(
            content: $htmlContent,
            header: content(
                Html('h2', [$classReflector->getShortName()]),
                new Markdown(
                    is_null($doc)
                        ? 'No description available.'
                        : $doc->description,
                ),
            ),
            footer: is_null($doc) ? null : $this->hashTags($doc->tags),
        );
    }

    private function templateExampleAccordion(Path $path): Accordion
    {
        $output = $this->runExample($path);
        $exampleCode = read_file($path->toString());

        return new Accordion(
            class_basename($this->component),
            [
                'Example Code' => new CodeBlock($exampleCode, 'php'),
                'Example Output' => $output,
                'HTML Output' => new CodeBlock(
                    $output->render()->toString(),
                    'html',
                ),
            ],
            Accordion::Options('h3'),
        );
    }

    private function resolveExampleFile(string $file): Path
    {
        return path($file)
            ->dirname()
            ->append(
                DIRECTORY_SEPARATOR,
                'Examples',
                DIRECTORY_SEPARATOR,
                basename($file),
            );
    }

    private function resolveDocFile(string $file): Path
    {
        return $this->resolveExampleFile($file)->replaceEnd('.php', '.md');
    }

    private function resolveViewFile(
        string $file,
        string|bool $customView,
    ): Path {
        if (is_string($customView)) {
            return path($customView);
        }

        return path($file)
            ->dirname()
            ->append(
                DIRECTORY_SEPARATOR,
                'HtmlViews',
                DIRECTORY_SEPARATOR,
                basename($file),
            )
            ->replaceEnd('.php', '.view.php');
    }

    /** @param null|string|string[] $tags */
    private function hashTags(string|array|null $tags): ?HtmlView
    {
        if (is_null($tags)) {
            return null;
        }

        return new InlineMarkdown(
            "\n\n #" . (is_string($tags) ? $tags : implode(' #', $tags)),
        );
    }

    /** Run an example file and return its rendered content. */
    private function runExample(Path $path): HtmlContent
    {
        try {
            // @mago-expect analysis:mixed-assignment
            $code = include $path;
            if ($code instanceof HtmlView) {
                return $code->template();
            }
            if ($code instanceof HtmlContent) {
                return $code;
            }

            throw new \RuntimeException(
                'Example does not return HtmlContent or HtmlView.',
            );
        } catch (\Throwable $th) {
            throw new \RuntimeException('Failed to run example: ' . $path->toString(), $th->getCode(), previous: $th);
        }
    }
}
