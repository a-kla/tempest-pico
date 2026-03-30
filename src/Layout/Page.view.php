<?php

declare(strict_types=1);

/**
 * Base HTML for [Pico](https://picocss.com) CSS styled pages
 *
 * TODO: Switch from pre-build to Sass
 * TODO: use post-processor to remove unused CSS
 *
 * TODO: How to add the JS a component needs?
 *
 * @var \TempestPico\Layout\Page $this
 *
 **/

use Tempest\Support\Uri\Uri; // used in template

use function Tempest\env; // used in template
use function Tempest\Support\Str\ensure_ends_with;

/*
 * Make $baseUrl available in the template.
 *
 * This is a workaround for the fact that Tempest sometimes puts the template in a function.
 * Static code analysis tools love this behavior! And I love wasting time debugging it! */
$scopedVariables['baseUrl'] = ensure_ends_with(Uri::from(env('LINK_TO', '/')), '/');

?>
<x-mybase :title="$this->title">
  <x-slot name="head">
    <link rel="stylesheet" href="<?= $baseUrl . ($this->isStatic ? 'static' : 'dynamic') ?>.css" />
    {{-- TODO: SEO view--}}
    <meta name="robots" content="noindex,nofollow">
  </x-slot>
  <header class="container">
    <hgroup>
      <h1>Tempest-Pico</h1>
      <p>Components for Tempest Framework with Pico CSS + UnoCSS </p>
    </hgroup>
    {!! ($this->mainNav)() !!}
  </header>
  <main class="container">
    <h1>{{ $this->title }}</h1>

    {!! ($this->main)() !!}
  </main>
</x-mybase>
