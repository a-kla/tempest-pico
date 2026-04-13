<?php

declare(strict_types=1);

/**
 * @var \TempestPico\Layout\MainNav $this
 *
 * FIXME: Add script for the Theme switcher
 **/

use Tempest\Support\Uri\Uri; // used in template

use function Tempest\env; // used in template
use function Tempest\Support\Str\ensure_ends_with;

// use function TempestPico\Support\Html\toHtml as _;

// Works as expected!
$baseUrl = ensure_ends_with(Uri::from(env('LINK_TO', '/')), '/');

?>
<nav>
  <ul>
    <?php foreach ($this->links as $url => $text): ?>
      <li><a href="<?= $baseUrl . $url ?>"><?= _($text) ?></a></li>
    <?php endforeach; ?>
  </ul>
  {{--
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
  --}}
</nav>
