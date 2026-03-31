# Some Components for [Tempest](https://tempestphp.com) using its Views and [PicoCss]((https://picocss.com/)) + UnoCss

[Example as GitHub Page](https://a-kla.github.io/tempest-pico/example)

## Why not tailwind?

I prefer semantic CSS (and HTML) + utility CSS for Modifiers (like the M in BEM). (+ Components with scoped CSS, if necessary).

My code is below /src, /app is the default Tempest (v2) example, just as static page + Header. 

Take a look at my [Example as GitHub Page](https://a-kla.github.io/tempest-pico/example) and compare the sources.

## Why don't you make more use of the View features?

I like to have my IDE and static code analysis tools understand my code as much as possible. I don't want to have to guess what variables are available in the template, or what methods I can call on $this. 

## Why you don't use Slots and Dynamic components?

Dynamic components only take a string as attribute, so you can't pass anything else.

## Tempest v3 is out, but i can't use it.

`composer create-project tempest/app=2` <= note the `=2` for PHP 8.4

Until the basic work is done, I have to stay on 8.4.
The unmaintained code i want to replace is not PHP 8.5 compatible. 


## Update the CSS while developing

```bash
$ pnpm unocss --watch
```

## deploy 

`composer gen` deletes all public/*.html files, generates the static sites again and then the css.

If you generate the Pages on the same Environment you develop: Use `./tempest static:clean` after deploying to remove the generated static files, so you don't use old files.

## Deploy to GitHub Pages

You can use my `.github/workflows/deploy.yml` if you want Tempest for your Github Pages.

## PRs and suggestions are welcome!

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
