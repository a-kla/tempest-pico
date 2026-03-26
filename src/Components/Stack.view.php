<?php
/**
 * var TempestPico\Components\Stack $this 
*/
?>
<x-template :foreach="$this->components as $component">
  {!! $component() !!}
</x-template>
