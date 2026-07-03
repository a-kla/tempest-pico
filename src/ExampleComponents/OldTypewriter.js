/*
import TW from 'typewriter-effect/dist/core';
*/

console.log("Typewriter Script loaded");

typewriter = new Typewriter('#Typewriter', {
  autoStart: true,
  loop: true,
});

typewriter
  .pauseFor(2500)
  .typeString('This is a old javascript. I do not need it anymore…')
  .pauseFor(300)
  .deleteChars(41)
  .typeString('nice <em>JavaScript</em> to show the use of the <strong>Asset Manager</strong>.')
  .pauseFor(1000)
  .start();
;