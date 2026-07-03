# Tempest Pico — Copilot Instructions

## Quick Start

**Tech Stack**: PHP 8.4, Tempest Framework (v2), Tempest-HtmlView, Pico CSS, PHPUnit, PHPStan (level 7), Mago

**Yohn's Fork of PicoCSS is used** - see <https://yohn.github.io/PicoCSS/> and <https://github.com/Yohn/PicoCSS/>

**HtmlView** is a component-style view layer. The HTML builder itself is **HtmlContent**, which is created via the `Html()` and `content()` helpers.

**QA Workflow**:
```bash
composer qa           # fmt + phpunit + lint (use this before PRs)
composer phpunit      # Run tests with detailed output
composer fmt          # Format with mago
composer lint         # Lint with mago (auto-fixes)
pnpm unocss --watch   # Watch CSS during development
```

**Key Namespaces**:
- `TempestPico\` → `/src/` (main application)
- `AKl\Tempest_HtmlView\` → `/packages/Tempest_HtmlView/src/` (HTML builder package)
- `Tests\` → `/tests/` (PHPUnit tests)

**PHPStan**: Level 7 strict checking on `src/`, `app/`, `tests/`

---

## Type Hints and Abbreviations

- `MD` → Markdown (GitHub flavored), helper: `MD()`
- `IMD` → Inline Markdown (no block elements like `<p>`), helper: `IMD()`
- If you see `MD`/`IMD` as a type hint, it means the corresponding helper is used on the given string.
- `(Component-)Content` can be one of:
  - `string|Stringable` → auto-escaped for safe output in HTML
  - `HtmlView` → any class extending `HtmlView` (aka component)
  - `HtmlContent` → the HTML tree builder object created by `Html()` / `content()`
  - `HtmlDocument` → PHP 8.4 `Dom\HtmlDocument`
  - `HtmlString` → raw HTML; use with care and prefer `Html()` or `Dom()` when possible
  - `View` → any other Tempest View
- `HtmlContent` is the main builder object. Use `Html()` and `content()` to create it.

---

## Project Principles

### 1. **Semantic HTML/CSS First**
- Prefer semantic HTML elements over generic divs.
- Avoid Tailwind-style utility-heavy markup; keep the markup readable.
- Prefer `HtmlContent` and `HtmlView` over string-based templates when building UI.

### 2. **IDE & Static Analysis Clarity**
- All template variables must be IDE-discoverable (no `$$key` magic, explicit type hints).
- Prefer promoted constructor properties for dependency injection.
- PHPStan level 7 catches undefined classes, type errors, and null access issues.
- Common issue: **undefined exception classes** → create them in the relevant `Exception/` folder.

### 3. **Component Architecture**
- Components live in `src/Components/`.
- Components should also provide:
  - an example returning the component / `content()` in `src/Components/Examples/*.php`
  - a short description or usage note in `src/Components/Examples/*.md`
- Extend the `HtmlView` base class.

### 4. **HtmlContent / HtmlView**
- **Purpose**: build HTML trees programmatically without string templates.
- **Key concepts**:
  - `Html()` creates an `HtmlContent` node and returns it for further chaining.
  - `content()` collects strings, views, and other HTML content into a single tree. Also creates an `HtmlContent`.
  - `__invoke()` and `appendContent()` adds children to the current node. Prefer the invoke method if the `Html()` helper is used.
  - `render()` converts the tree to an `HtmlString`.
- **Common pattern**:
  ```php
  Html('div')
      ('h1', 'Headline');
  ```

---

## Common Tasks

### Add a New Component

1. Use `src/Components/Accordion.php` or `src/Components/Table.php` as a reference.
2. Create `src/Components/*.php` with the component logic and dependencies.
3. Extend `HtmlView`.
4. Reuse the component in other components or controllers.
5. Add a usage example and short notes under `src/Components/Examples/` when appropriate.

### Add Examples, Short Documentation, and Tests for a Component

- Place usage examples in `src/Components/Examples`.
- Place short notes in `src/Components/Examples/*.md`.
- Keep notes concise; a known limitation or a useful link is enough.
- Use the example in tests under `tests/` when relevant.

### Add a Helper Function
- Prefer existing helpers from the `AKl\Tempest_HtmlView` package.
- If a new helper is needed, add it to `packages/Tempest_HtmlView/src/functions.php` or the closest package file.
- Import helpers via `use function AKl\Tempest_HtmlView\...`.

### Run Tests Before PR
```bash
composer qa
```
This ensures:
- Code is formatted with `mago fmt`
- All tests pass with PHPUnit
- Linting passes with `mago lint --fix`

### Debug HtmlContent Issues
- `render()` returns an `HtmlString`.
- PHPStan will catch missing exception definitions, invalid imports, and type mismatches.

---

## Architecture Overview

```text
src/
  Components/         # Reusable UI components
    *.php             # Logic
    HtmlViews/
      *.view.php      # Rendered output, auto-generated
    Examples/
      *.php           # Example usage
      *.md            # Notes / documentation
  Layout/             # Page layout (header, nav, footer) and configs
    Page/             # Base HTML for pages
  Page/               # Route handlers / page builders / controllers
packages/
  Tempest_HtmlView/
    src/              # HTML builder package
tests/                # PHPUnit tests
```

---

## Key Files & References

- [GitHub Page](https://a-kla.github.io/tempest-pico/doc/) — final generated documentation for this project
- [Tempest Framework](../vendor/tempest/framework/docs/) — Tempest documentation
- `./tempest --help` — available Tempest commands
- [Yohn's PicoCSS Fork](https://github.com/Yohn/PicoCSS?tab=readme-ov-file) — styled HTML elements and CSS classes
- [Accordion](../src/Components/Accordion.php) and [Table](../src/Components/Table.php) — good examples for components
- [README.md](../README.md) — project rationale and deployment notes
- [phpstan.neon](../phpstan.neon) — static analysis config (level 7)
- [composer.json](../composer.json) — QA scripts and autoloading

---

## PHPStan Common Fixes

### ❌ "Undefined type 'AKl\\Tempest_HtmlView\\Exception\\InvalidTag'"
**Fix**: create the exception class in the relevant package folder, for example `packages/Tempest_HtmlView/src/Exception/InvalidTag.php`:
```php
<?php declare(strict_types=1);

namespace AKl\Tempest_HtmlView\Exception;

class InvalidTag extends HtmlViewException implements HasContext
{
    public function __construct(private string $tag)
    {
        parent::__construct("The HTML tag {$tag} is unknown.");
    }

    public function context(): array
    {
        return ['tag' => $this->tag];
    }
}
```

Then import it:
```php
use AKl\Tempest_HtmlView\Exception\InvalidTag;
```

### ❌ "Cannot access offset on mixed"
**Fix**: add explicit array type hints:
```php
/** @var array<string, string> $config */
$config = $arr->toArray();
```

---

## PHPUnit & Testing

- Tests generally inherit from `IntegrationTestCase` unless they are unit-only.
- Test file naming: `*Test.php` in `/tests/`.
- Run with: `composer phpunit`.

---

## Deployment & Static Generation

```bash
# Generate static HTML (GitHub Pages)
./tempest static:generate --verbose

# Clean after deploy
./tempest static:clean --force
```

---

## Git Workflow

Before pushing:
1. Run `composer qa` and make sure it passes.
2. Verify there are no PHPStan issues if relevant to your change.
3. Commit with a short, meaningful message.

---

## Tips for AI Agents

- Run `./tempest routes --json` when you need data about available pages or routes.
- Keep doc blocks short and only add type hints or annotations when they improve clarity.
- Run `composer qa` after changes to catch formatting, test, and lint issues.
- PHPStan level 7 is strict and will surface undefined classes, missing imports, and type mismatches.
- Prefer semantic HTML over generic divs, and review existing components for the local pattern.

If anything in this guidance is unclear, tell me and I can refine it further.
- **HTML View Tree Builder is still evolving** — check tests for expected behavior before guessing
- **Make use of Tempest Helpers** — check `vendor/tempest/framework/packages/support/src/*/functions.php`
- **Don't create loose Exception files** — always implement `HasContext`