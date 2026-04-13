<?php

declare(strict_types=1);

namespace TempestPico\Components;

use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\Attributes\AttributesExtension;
use League\CommonMark\Extension\Autolink\AutolinkExtension;
use League\CommonMark\Extension\InlinesOnly\InlinesOnlyExtension;
use League\CommonMark\Extension\SmartPunct\SmartPunctExtension;
use League\CommonMark\Extension\Strikethrough\StrikethroughExtension;
use League\CommonMark\MarkdownConverter;
use Tempest\Container\Container;
use Tempest\Container\Initializer;
use Tempest\Container\Singleton;

final class InlineMarkdownInitializer implements Initializer
{
    #[Singleton('inline')]
    public function initialize(Container $container): MarkdownConverter
    {
        $config = [
            // security settings
            'html_input' => 'escape',
            'allow_unsafe_links option' => false,
            'max_nesting_level' => 20,
            'max_delimiters_per_line' => 100,

            'attributes' => [
                'allow' => ['id', 'class', 'align', 'title'],
            ],
            'autolink' => [
                'allowed_protocols' => ['https'], // defaults to ['https', 'http', 'ftp']
                'default_protocol' => 'https', // defaults to 'http'
            ],
            'smartpunct' => [
                'double_quote_opener' => '„',
                'double_quote_closer' => '“',
                'single_quote_opener' => '‘',
                'single_quote_closer' => '’',
            ],
        ];
        $environment = new Environment($config);

        $environment
            ->addExtension(new InlinesOnlyExtension()) // needs to be the first!
            // ->addExtension(new CommonMarkCoreExtension())
            ->addExtension(new AutolinkExtension())
            ->addExtension(new StrikethroughExtension()) // TODO: find a Extension for <ins>
            ->addExtension(new SmartPunctExtension())
            ->addExtension(new AttributesExtension());
        return new MarkdownConverter($environment);
    }
}
