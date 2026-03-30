<?php

declare(strict_types=1);

/** @var TempestPico\Components\Table $this */
// FIXME: tell mago $this is set

use function Tempest\Support\Arr\has_key;
use function Tempest\Support\Arr\keys;
use function TempestPico\Support\_;
use function TempestPico\Support\composeStr;
use function TempestPico\Support\renderContent;

$renderCell = function (int $col, string $row): string {
    if (has_key($this->cells, $col) && has_key($this->cells[$col], $row)) {
        return renderContent($this->cells[$col][$row]);
    }

    return renderContent($this->options['fallback']);
};

$class = composeStr([
    'striped' => $this->options['striped'],
    'overflow-auto' => $this->options['scrollable'],
]);

/*
 * Yahoo it is 2026 and my Template Code looks like my PHP Code from 1999!
 *
 * At least I have code completion and it is checked by Mago and other tools…
 */
?>
<table :class="$class">
  <?php if ($this->options['caption'] !== null): ?>
    <caption><?= _($this->options['caption']) ?></caption>
  <?php endif ?>
  <thead>
    <tr>
      <?php foreach ($this->head as $cell): ?>
        <th scope="col"><?= renderContent($cell) ?></th>
      <?php endforeach; ?>
    </tr>
  </thead>
  <tbody>
    <?php foreach (keys($this->cells) as $colId): ?>
      <tr>
        <?php foreach (keys($this->head) as $rowId):
            if ($rowId === $this->primaryRow): ?>
              <th scope="row"><?= $renderCell($colId, $rowId) ?></th>
            <?php else: ?>
              <td><?= $renderCell($colId, $rowId) ?></td>
            <?php endif; ?>
        <?php endforeach; ?>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
