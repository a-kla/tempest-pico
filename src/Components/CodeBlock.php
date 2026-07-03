<?php

declare(strict_types=1);

namespace TempestPico\Components;

use AKl\Tempest_HtmlView\HtmlContent;
use AKl\Tempest_HtmlView\HtmlView;
use Tempest\Highlight\Highlighter;
use Tempest\Highlight\Themes\InlineTheme;
use Tempest\Support\Html\HtmlString;
use Tempest\Support\Path\Path;

use function AKl\Tempest_HtmlView\Html;
use function Tempest\Support\Filesystem\read_file;
use function Tempest\Support\path;

/**
 * @phpstan-type Opt = array{showFileName: false | 'relativeToRoot' | 'short'}
 */
#[
    Doc('A semantic component for displaying syntax-highlighted code blocks.', [
        'Helper',
    ]),
]
final class CodeBlock extends HtmlView
{
    private readonly string $code;
    private array $options;

    /**
     * @param string|Path $codeOrFile the code to display or a path to a file containing the code.
     * @param 'php'|'html'|'js'|'text'|string $language the language of the code for syntax highlighting.
     * @param Opt $options
     */
    public function __construct(
        string|Path $codeOrFile,
        public string $language,
        ?array $options = null,
    ) {
        parent::__construct();
        $this->options = $options ?? self::Options();

        $this->code = is_string($codeOrFile)
            ? $codeOrFile
            : match ($this->options['showFileName']) {
                'relativeToRoot' => "// {$codeOrFile
                    ->replaceStart(
                        __DIR__ . DIRECTORY_SEPARATOR,
                        '',
                    )
                    ->toString()}\n",
                'short' => "// {$codeOrFile->basename()->toString()}\n",
                default => '',
            } . read_file($codeOrFile->toString());
    }

    public function template(): HtmlContent
    {
        //TODO: use Initializer + config
        /** @var null|'dark'|'light' sets data-theme attribute for styling */
        $picoTheme = 'dark';
        $highlighter = new Highlighter(
            new InlineTheme(
                path(__DIR__)
                    ->dirname()
                    ->dirname()
                    ->append(
                        '/vendor/tempest/highlight/src/Themes/Css/dark-plus.css',
                    )
                    ->toString(),
            ),
        );

        $htmlString = new HtmlString(
            $highlighter->parse($this->code, $this->language),
        );

        return Html(
            'pre',
            attributes: ['data-theme' => $picoTheme],
        )(
            'code',
            $htmlString,
            ['data-lang' => $this->language],
        );
    }

    /**
     * @param Opt['showFileName'] $showFileName show the file name if the code was loaded from a file.
     *
     * @return Opt
     */
    public static function Options(
        false|string $showFileName = 'relativeToRoot',
    ): array {
        return [
            'showFileName' => $showFileName,
        ];
    }
}
