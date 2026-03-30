<?php

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
?>
<x-mybase :title="$this->title">
  <x-slot name="head">
    <link rel="stylesheet" href="static.css" :if="$this->isStatic">
    <link rel="stylesheet" href="dynamic.css" :else>
    <!-- TODO: SEO view-->
    <meta name="robots" content="noindex,nofollow">
  </x-slot>
  <header class="container">
    <hgroup>
      <h1>Tempest-Pico</h1>
      <p>Components for Tempest Framework with Pico CSS + UnoCSS </p>
    </hgroup>
    <nav>
      <ul>
        <li><a href="/">Default Tempest (pur Tailwind) Example</a></li>
        <li><a href="/example">My Example & Components</a></li>
        <li><a href="/tables">Component: Table</a></li>
      </ul>
      <ul>
        <li>
          <details class="dropdown">
            <summary role="button" class="secondary">Theme</summary>
            <ul>
              <li><a href="#" data-theme-switcher="auto">Auto</a></li>
              <li><a href="#" data-theme-switcher="light">Light</a></li>
              <li><a href="#" data-theme-switcher="dark">Dark</a></li>
            </ul>
          </details>
        </li>
      </ul>
    </nav>
  </header>
  <main class="container">
    <h1>{{ $this->title }}</h1>

    {!! ($this->main)() !!}
  </main>
</x-mybase>
