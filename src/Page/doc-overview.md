## Asset Manager for Tempest

You do not need Node/Vite to get versioned assets. *Tempest-HtmlView* includes its own asset manager for this purpose.

It does not bundle assets into a single file. With HTTP/2, this is not the performance advantage it once was.
*Maybe* this feature will be added later. (PRs are welcome)

Note: [Symfony's Asset Mapper](https://symfony.com/doc/current/frontend/asset_mapper.html) is very powerful. Unfortunately, I was not able to integrate it without pulling in most of the Symfony framework. If you prefer Twig, this may be a good option for you.

## Tempest-HtmlView

### HtmlView — A *dedicated view object* for Views with HTML Output (aka Components)

`HtmlView implements Tempest\View`

It adds methods that, in my opinion, improve the DX of Tempest\View.
For example, it automatically includes versioned `*.css` and `*.js` files when they share the same filename as an HtmlView.

### HtmlContent (shortcut: `Html()` and `content()`)

The main difference between HtmlView and Tempest\View is the `template()` method, which offers another way to write HTML templates.

```php
public function template(): HtmlContent
{
    return content(
        // auto escaped string
        'Hello ',
        // HTML element with auto escaped text (strings) or other non-escaped content
        Html('strong', $this->name),
        // a View (no matter HtmlView or Tempest\View)
        new ShowPosts(since: $this->lastLogin),
    );
}
```

Is the same as:

```html
// *.view.php

Hello <strong>{{ $this->name }}</strong>
{% new ShowPosts(since: $this->lastLogin) %}
```

Note that the first is PHP code, the second just Text (HTML) for my IDE and analytic tools.

#### `Html()`

`Html()` is a bit like [laravel-html](https://packagist.org/packages/spatie/laravel-html), but much simpler.

```php
use function Tempest\Support\Arr\map_iterable;

$list = fn (
    $items = [],
    $order = true,
) => Html(
    $order ? 'ol' : 'ul',
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

#### Content

The `$content` parameter of `Html()` and `content()` can be a *string* (auto-escaped), another `Html()`, a view/component, HTML (DOM fragment), or an array of those (see `map_iterable()` in the example above).
This makes them very flexible.

### It does not replace Tempest\View

As said before you can mix it with normal Tempest views.

You can also use plain HTML with PHP's `Dom\HtmlDocument` (`Dom()` shortcut) and manipulate it like in JavaScript.

Take a look at the [Examples](/doc/examples) and [Components](/doc/components), and then use whatever gives you the better DX.

## Why?

The goal of this project is to provide a solid base for building *mostly* static websites quickly, especially for people who do not care much about styling at the moment.

In my opinion, Tempest needs a starter kit for simple sites.

> I just need a simple dashboard with one button, ASP!

In the long run, I would like to replace some WordPress sites with Tempest. \
Unfortunately, Tempest's own view system is not really suitable for me,
which is why I created **HtmlView**.

It is not battle-tested, but it solved several issues I had. I am happy with it so far, even though it does not match the DX of [XHP](https://github.com/hhvm/xhp-lib).

Some of the issues are:

- no proper IDE support
- I dislike having two files for one view (the dedicated view object (DVO) and the `.view.php` file).
  The `.view.php` file is now generated automatically, because it only needs to contain `$this->toHtml()`.
- Tempest sometimes moves parts of the template into a function. So sometimes `$foo = 'bar';` works,
  while the next time you need to write `$scopedVariables['foo'] = 'bar';` in your `.view.php`.
- and some other things that have been solved in Tempest v3.

Tempest also needs more examples of how it is used in real life. Oh, by the way:

### Do you want your Tempest project (or its docs) as GitHub Pages?

- You can use my `.github/workflows/deploy.yml`. Modify it if you want PHP 8.5 and Tempest 3.
- *Don't forget*: copy and **update the `.env` file**. Especially **DEPLOY_PATH** — make sure you use it for links.
- You also need to create an empty `public/.nojekyll` file.

## Feedback is welcome

For around 10 years, I only used PHP for small scripts and to fix WordPress plugins, so I am a bit rusty and not fully up to date with modern PHP development.

So feel free to give me feedback like: "Hey, you should use this library." or "This is not how you should do it in 2026; you should do it like this!"
