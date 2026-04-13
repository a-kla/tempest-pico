<?php

declare(strict_types=1);

namespace TempestPico\Components;

use Tempest\Highlight\Highlighter;
use Tempest\Highlight\Themes\InlineTheme;
use Tempest\Support\Html\HtmlString;
use Tempest\Support\Path\Path;
use TempestPico\Support\Html\HtmlViewTree;

use function Tempest\Support\Filesystem\read_file;
use function Tempest\Support\path;
use function TempestPico\Support\Html\Html;

#[Doc('A semantic component for displaying syntax-highlighted code blocks.', ['Custom'])]
final class CodeBlock implements Component
{
    use IsComponent;

    private string $code;

    /**
     * @param 'php'|'html'|'js'|'text'|string $language
     */
    public function __construct(
        string|Path $codeOrFile,
        public string $language,
    ) {
        $this->code = match (true) {
            is_string($codeOrFile) => $codeOrFile,
            default => read_file($codeOrFile->toString()),
        };

        $this->setPaths();
    }

    public function getViewTree(): HtmlViewTree
    {
        //TODO: use Initializer + config
        /** @var null|'dark'|'light' sets data-theme attribute for styling */
        $picoTheme = 'dark';
        $HL = new Highlighter(new InlineTheme(
            path(__DIR__)
                ->dirname()
                ->dirname()
                ->append('/vendor/tempest/highlight/src/Themes/Css/dark-plus.css')
                ->toString(),
        ));

        $code = new HtmlString($HL->parse($this->code, $this->language));

        return Html('pre', [], ['data-theme' => $picoTheme])(
            'code',
            [$code],
            ['data-lang' => $this->language],
        );
    }
}
