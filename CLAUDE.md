# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this project is

Uppa front-end — a Laravel 13 + Livewire 4 + Flux UI application whose core feature is a mushroom-recognition workflow ("reconocimiento de hongos"). The Laravel app is a thin UI shell: image uploads are forwarded to an external Uppa API (`UPPA_API_URL`) which performs the classification and returns a JSON result that the UI renders. Authentication is handled by Laravel Fortify with Livewire-rendered views. The UI is in Spanish.

## Common commands

```bash
# One-shot setup (installs deps, copies .env, runs migrate, builds assets)
composer setup

# Dev loop (artisan serve + queue:listen + vite — runs concurrently)
composer dev

# Lint (Laravel Pint)
composer lint        # apply
composer lint:check  # verify only

# Tests (Pest 4 — runs config:clear, lint:check, then artisan test)
composer test
./vendor/bin/pest                                  # raw runner
./vendor/bin/pest tests/Feature/DashboardTest.php  # single file
./vendor/bin/pest --filter='dashboard is shown'    # single test by name
```

CI requires `FLUX_USERNAME` / `FLUX_LICENSE_KEY` secrets (see `workflows/lint.yml`, `workflows/tests.yml`) — Flux UI is a commercial package installed via composer http-basic against `composer.fluxui.dev`.

## Required environment

- `UPPA_API_URL` — base URL of the external mushroom-classification API. Without it, `UppaApiService::catalogarHongo()` will fail. See `config/services.php` (`uppa_api.url`).
- Default DB is SQLite (`database/database.sqlite`); cache, sessions, and queue all default to the `database` driver.

## Architecture

### Livewire 4 single-file component convention

This codebase uses the **view-based single-file Livewire component** pattern. Filenames with a leading `⚡` (lightning emoji) signal a Livewire-controlled view. The pattern comes in two shapes:

1. **Standalone page**: a single `⚡foo.blade.php` file containing both `@php new class extends Component {...}` and the markup (see `resources/views/pages/settings/⚡profile.blade.php`).
2. **Paired component**: a directory with `upload.blade.php` (markup) + `upload.php` (the anonymous `new class extends Component`). Example: `resources/views/components/file/⚡upload/`. The `.php` file is the component class; the `.blade.php` is its template. This pattern is unusual — when adding new file-upload-style components, follow the same paired layout.

`config/livewire.php` defines two component namespaces:
- `pages::*` → `resources/views/pages/` (used by `Route::livewire('settings/profile', 'pages::settings.profile')`)
- `layouts::*` → `resources/views/layouts/` (the default `component_layout` is `layouts::app`)

`Route::livewire(...)` (used in `routes/settings.php`) wires a URL directly to a single-file component, no controller required. Traditional controller routes (`routes/web.php` → `ReconocimientoController`) coexist with this pattern.

### Reconocimiento flow (the core feature)

```
User → /reconocimiento (web.php)
     → ReconocimientoController@index
     → vistas/reconocimiento/index.blade.php
     → <livewire:file.upload />            (resources/views/components/file/⚡upload/)
     → upload.php::analizar(UppaApiService)
     → UppaApiService::catalogarHongo($path)  (POSTs multipart 'file' to {UPPA_API_URL}/analizar)
     → JSON result rendered as <pre> in the view
```

`UppaApiService` is registered as a singleton in `AppServiceProvider::register()` and resolved via method injection on the Livewire `analizar` action.

### Auth (Fortify + Livewire)

- `app/Providers/FortifyServiceProvider.php` wires Fortify's auth views to Livewire pages under `pages::auth.*`.
- Custom user creation / password reset live in `app/Actions/Fortify/` and share validation via `app/Concerns/{Password,Profile}ValidationRules.php`.
- 2FA challenge has a dedicated rate limiter (5/min keyed by `login.id` session value).
- Settings routes apply `password.confirm` middleware conditionally based on Fortify's 2FA-confirm-password feature flag.

### Frontend / theming

- Tailwind CSS v4 via `@tailwindcss/vite` (no `tailwind.config.js` — config is in `resources/css/app.css` using `@theme`/`@source`).
- Flux UI (`livewire/flux` + flux-pro stubs) provides `<flux:*>` components used throughout layouts.
- Custom colors `--color-golden-pollen` and `--color-vintage-grape` are defined in `app.css`. `paleta.txt` holds the **full color palette source** (golden-pollen, vintage-grape, lobster-pink, muted-olive, ocean-blue, orange, each with a 50–950 scale) — copy from there when extending the theme rather than picking new colors.
- Dark mode uses a custom variant `.dark` (root `<html>` is hard-coded `class="dark"` in `layouts/app/sidebar.blade.php`).

### Sidebar item pattern

`<x-item-sidebar icon="..." icon-hover="..." ruta="route.name" texto="..." />` is the project's sidebar nav primitive (`resources/views/components/item-sidebar.blade.php`). It auto-detects the current route via `request()->routeIs($ruta)`, swaps icons on hover via `components/sidebar-icon.blade.php`, and disables `wire:navigate` when already on the page. Note: the corresponding `App\View\Components\item-sidebar` PHP class exists but is not used in resolution — the Blade-only anonymous component pattern is what's wired in.

### App bootstrap

`bootstrap/app.php` trusts all proxies (`trustProxies(at: '*')`) — required because the app is intended to run behind a reverse proxy. Don't tighten this without understanding the deployment target.

`AppServiceProvider::configureDefaults()` enforces:
- `CarbonImmutable` as the default date class
- Destructive DB commands prohibited in production
- Password rules min(12) + mixedCase + numbers + symbols + uncompromised, **only in production** (dev has no constraints)

## Conventions

- Domain naming is **Spanish**: `reconocimiento` (recognition), `catalogarHongo` (catalog mushroom), `analizar` (analyze), `vistas` (views), `foto` (photo). New domain code should follow the same language.
- Tests use **Pest 4**, not PHPUnit directly. New tests go in `tests/Feature/` (or `tests/Unit/` for pure logic) and use Pest's `it()`/`test()` syntax — see existing files for shape.
- Pint preset is `laravel` (`pint.json`). Run `composer lint` before committing — `composer test` will fail on style violations.
