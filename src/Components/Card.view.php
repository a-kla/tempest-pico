<?php

/** @var TempestPico\Components\Card $this
 *
 * BUG: $this->style in HTML - fixed in tempest 3.0
 */

/* NOTE: :isset don't work with properties */
// echo isset($this->header) ? '✔️' : '❌';
?>
<?= $this() ?>
