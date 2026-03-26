<?php

/** @var TempestPico\Components\Massages $this */

use TempestPico\Components\Card;
use TempestPico\Components\Markdown;
use TempestPico\Components\Stack;

use function Tempest\Support\Arr\map_iterable;

$cards = new Stack(
  map_iterable(
    $this->msgs,
    function ($msg) {
      switch ($msg['type']) {
        case 'error':
          return new Card(
            content: new Markdown($msg['md']),
            header: new Markdown('**Error**'), // TODO: inline MD
            class: '; outline-4 outline-double outline-red',
            style: ';
              --pico-card-background-color: #ff000060;
              --pico-card-border-color: rgb(248 113 113);
              --pico-card-sectioning-background-color: #ff0000c0;
            '
          );

        case 'warning':
          return new Card(
            content: new Markdown($msg['md']),
            header: new Markdown('**Warning**'),
            // Pico use borders only inside the card
            class: '; border border-solid border-amber dark:border-amber-600',
            style: ';
              --pico-card-border-color: light-dark(#f2df0d, #e17100);
              --pico-card-background-color: light-dark(#f2df0d33, #ffbf0033);
              --pico-card-sectioning-background-color: light-dark(#f2df0d, #e17100);
            '
          );

        default:
          return new Card(
            content: new Markdown($msg['md']),
            style: ';
              --pico-card-background-color: light-dark(#9bccfd, #1343a0);
            '
          );
      }
    }
  )
)
?>
{!! $cards() !!}