# Some Components for [Tempest](https://tempestphp.com)

## This is just a preview…

> [!IMPORTANT]
> ⚠️ Tempest-Pico is still a work in progress and built on top of Tempest **v2** (I still need *PHP 8.4*).

Right now I use [Yohns PicoCSS Fork](https://yohn.github.io/PicoCSS/), but i like to replace it by Components with almost no style
(headless) + extra CSS. So you can switch to your own CSS,
~~ACSS~~ ~~Bootstrap~~ ~~Tailwind~~ ~~MaterializeCSS~~ ~~UnoCSS~~ daisyUI
or what ever is the hyped Framework right now.

This Doc and the Components use my `Tempest-HtmlView`.
*Tempest-HtmlView* allows me to build HTML programmatically and with full IDE support.
It is a bit like
[laravel-html](https://packagist.org/packages/spatie/laravel-html), but much simpler.

```php
// laravel-html:
$text = 'Hello world!'; // only null or string
html()-p()->span()->text($text);

// Tempest-HtmlView:
$wrapper = 'p'; // or null or any other non-void-element
$content = 'Hello world!' // can be another `view`/Component 
Html($wrapper)('span', $content);

//Tempest\View:
<div :is="$wrapper"><span>{{ $text }}</span></p> // I'm not sure `:is` will work 

/* Example where HtmlView has a better DX then Tempest\View IMHO: */

use function Tempest\Support\Arr\map_iterable;

$list = fn (
    $items = [],
    $ordert = true,
) => Html(
        $ordert ? 'ol' : 'ul',
        attributes: ['class' => 'list'],
        content: map_iterable(
            $items,
            static fn($item, $id) => Html('li', "Item #{$id} {$item}"),
        ),
    )->render();

echo $list([1 => 'Foo', 4 => 'Bar', 9 => 'Baz']);
```

```html
<!-- formatted output: -->
<ol class="list">
    <li>Item #1 Foo</li>
    <li>Item #4 Bar</li>
    <li>Item #9 Baz</li>
</ol>
```

Tempests mandatory `*.view.php` files are created automatically as `{!! $this()->toHtml() !!}` is the only required content.

See more examples on the [GitHub Page](https://a-kla.github.io/tempest-pico/doc/components).

## FAQ

### Why don't you use Tempest's View?

I like to have my IDE and static code analysis tools understand my code as much as possible.
I don't want to have to guess what variables are available in the template, or what methods I can call on `$this`.
And I ran into some View Issues.

### But I use Tempest's Views…

You can mix it with the normal Tempest Views, so you can use it only for some parts of your project.

`HtmlView implements View` So see *HtmlView* as a extension to Tempest View, not as a replacement.

HtmlView also supports you to [use plane HTML5](https://a-kla.github.io/tempest-pico/doc/dom) with the `Dom()` shortcut and manipulate it JS like using [`$dom->querySelector()`](https://www.php.net/manual/en/dom-parentnode.queryselector.php).

### Why you don't use Slots and Dynamic components?

Dynamic components only take a string as attribute, so you can't pass anything else.

## Tempest v3 is out, but i can't use it

`composer create-project tempest/app=2` <= note the `=2` for PHP 8.4

Until the basic work is done, I have to stay on 8.4.
The unmaintained code i want to replace is not PHP 8.5 compatible (And needs a rewrite).

## Tip

If you generate the Pages in the same Environment you develop: Run `./tempest static:clean` after deploying to remove the generated static files, so you don't use old files.

## Deploy to GitHub Pages

You can use my `.github/workflows/deploy.yml` if you want Tempest for your Github Pages.

## PRs and suggestions are welcome

----

Check out the [Tempest documentation](https://tempestphp.com)·

Join the [Tempest Discord](https://tempestphp.com/discord) server.
