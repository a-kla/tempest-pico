<?php

/** @var TempestPico\Components\Card $this 
 * 
 * BUG: $this->style in HTML - fixed in tempest 3.0
*/

/* NOTE: :isset don't work with properties */
# echo isset($this->header) ? '✔️' : '❌';

?>
<article :class="$this->class" :style="$this->style">
  <header :if="$this->header !== null">{!! ($this->header)() !!}</header>
  {!! ($this->content)() !!}
  <footer :if="$this->footer !== null">{!! ($this->footer)() !!}</footer>
</article>

