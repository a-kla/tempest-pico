<?php

declare(strict_types=1);

/**
 * @var \TempestPico\Layout\MainNav $this
 *
 * FIXME: Add script for the Theme switcher
 **/

use Tempest\Support\Uri\Uri; // used in template

use function Tempest\env; // used in template
use function TempestPico\Support\_;

$baseUrl = Uri::from(env('BASE_URI', '/'));

?>
<nav>
  <ul>
    <?php foreach ($this->links as $url => $text): ?>
      <li><a href="<?= $baseUrl->withPath($url) ?>"><?= _($text) ?></a></li>
    <?php endforeach; ?>
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
