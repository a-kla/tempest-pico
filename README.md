# Components for Tempest View based on PicoCss + UnoCss

I dislike how Tailwind is used.
I prefer semantic CSS (and HTML) + utility CSS for Modifiers (like the M in BEM). (+ Components with scoped CSS, if necessary)

My code is below /src, /app is the default Tempest (v2) example, just as static page + Header. 

Take a look at my [Example as GitHub Page](https://a-kla.github.io/tempest-pico/example) and compare the sources.

## Tempest v3 is out, but i can't use it.

`composer create-project tempest/app=2` <= note the `=2` for PHP 8.4

Until the basic work is done, I habe to stay on 8.4.
The unmaintained code i want to replace is not PHP 8.5 compatible. 


## generate the css

### develop

```bash
$ pnpm unocss --watch
```

### deploy

`pnpm unocss && ./tempest static:generate`


----

<p align="center">
  <a href="https://tempestphp.com">
    <img src="https://raw.githubusercontent.com/tempestphp/.github/refs/heads/main/.github/tempest-logo.svg" width="100" />
  </a>
</p>

<h1 align="center">Tempest scaffold</h1>
<div align="center">
  This repository contains the default scaffold for Tempest.
  <br />
  The source code for the framework itself can be found at <code><a href="https://github.com/tempestphp/tempest-framework">tempestphp/tempest-framework</a></code>.
	<br />
	<br />
  <pre><div align="center">composer create-project tempest/app</div></pre>
	<br />
	<br />
	<sub>
		Check out the <a href="https://tempestphp.com">documentation</a>
		&nbsp;
		·
		&nbsp;
		Join the <a href="https://tempestphp.com/discord">Discord</a> server
  </sub>
</div>
