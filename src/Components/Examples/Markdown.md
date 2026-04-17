Uses [league/commonmark](https://commonmark.thephpleague.com/2.x/) with close to all Extensions.

## Features

- [x] Full Markdown (Tempests x-markdown only use core Markdown)
- [x] CSS: [Pico](https://picocss.com/) + [UnoCSS](https://)
- [x] Front Matter support
- [ ] Makes use of Front Matter


- HTML like <a /> is displayed
- [Pico](https://picocss.com/) as base CSS for semantic HTML without scores and scores of classes.
- ~~ACSS~~ UnoCSS for additional CSS

## Tailwind-like class names { class="bg-pico-primary-background text-pico-primary-inverse text-center" }

You can add **CSS inside Markdown**{class="text-3xl underline"}, just run `pnpm unocss --watch` and add `{class="…"}` to your MD (or Component).

The Headline above is styled with `{ class="bg-pico-primary-background text-pico-primary-inverse text-center" }`, wich make use of the [Pico custom properties](https://picocss.com/docs/css-variables).

{ class="text-pico-primary" }
This paragraf is styled using the class `text-pico-primary`

> I like the short class names of [the good old ACSS](https://acss.io). May I go back to it.

{.striped .border-blue}
| CSS | ACSS class | Tailwind class |
| :-------: | :------ | -------: |
| color:red; | C(red) | text-red |
| text-align: justify; | Ta(j) | text-justify |
| text-align-last: center; | Tal(c) | {I don't know} |

`.striped` is a Pico class for striped Tables btw.
The shortcut `{.class}` only works for known classes or static Pages. UnoCSS (and other utilities) don't detect classes without `class=`. For static Pages I simply run UnoCSS over the generated HTML.


> I still miss [XHP](https://github.com/phplang/xhp)! Every time I try a *“modern”* PHP template engine, I feel like I’m **back in the Stone Age**.

## TODO: 

1. Make more Components
2. Upgrade to Tempest v3
3. Improve Doc/example
6. see in-source `TODO:`s
3. much more…
