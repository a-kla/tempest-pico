<?php

declare(strict_types=1);

namespace AKl\Tempest_HtmlView;

use AKl\Tempest_HtmlView\HtmlView;
use ReflectionClass;
use Tempest\Console\Console;
use Tempest\Console\ConsoleArgument;
use Tempest\Console\ConsoleCommand;

use function Tempest\Support\path;

final class Commands
{
    public function __construct(private readonly Console $console) {}

    #[
        ConsoleCommand(
            name: "htmlview:create",
            description: "Generate the *.view.php for a specific HtmlView class.",
            aliases: ["make:htmlview"],
        ),
    ]
    public function __invoke(
    #[ConsoleArgument(
        'for',
        description: "Classname of the of the Html"
    )]    
    string $class): void
    {
        if (!class_exists($class)) {
            $this->console->error("Class [{$class}] does not exist.");

            return;
        }

        if (!is_a($class, HtmlView::class, true)) {
            $this->console->error(
                sprintf("Class [%s] must extend %s.", $class, HtmlView::class),
            );

            return;
        }

        /** @var class-string<HtmlView> $class */
        $view = new $class();
        $reflection = new ReflectionClass($view);

        $fileName = $reflection->getFileName();

        if ($fileName === false) {
            $this->console->error(
                sprintf("Could not determine the file path for [%s].", $class),
            );

            return;
        }

        $viewPath = path($fileName)
            ->dirname()
            ->append(\DIRECTORY_SEPARATOR, $view->path);

        if ($view->createViewFile($viewPath)) {
            $this->console->success(
                sprintf(
                    "Created view file for [%s] at %s.",
                    $class,
                    $viewPath->toString(),
                ),
            );

            return;
        }

        $this->console->info(
            sprintf(
                "View file already exists for [%s] at %s.",
                $class,
                $viewPath->toString(),
            ),
        );
    }
}
