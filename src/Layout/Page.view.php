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

use function Tempest\env; // used in template ?>
<x-mybase :title="$this->title">
  <x-slot name="head">
    <link rel="stylesheet" href="<?= Uri::from(env('BASE_URI', '/'))->withPath($this->isStatic ? '/static.css' : '/dynamic.css') ?>">
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
