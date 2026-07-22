# Съвет по изкуствен интелект — БТПП (AI Council at BCCI)

Bilingual (BG/EN) website for the AI Council, an advisory body to the Bulgarian
Chamber of Commerce and Industry. Content is managed through a Filament admin
panel; the public site is built with full-page Livewire components and is
optimised for SEO and GEO (generative-engine optimisation).

## Stack

- **Laravel 13**, PHP 8.4
- **Filament 5** — admin / CMS
- **Livewire 4** — full-page components
- **Tailwind CSS 4** (Vite) — design tokens ported from the approved design
- **SQLite** by default (any Laravel-supported DB works)

## Getting started

```bash
composer install
npm install
cp .env.example .env        # already provided; sets APP_LOCALE=bg
php artisan key:generate    # if needed
php artisan migrate:fresh --seed
php artisan storage:link
npm run build               # or: npm run dev
php artisan serve
```

The site seeds itself from the design in **both languages**, so it looks complete
on first load. Visit `/` (Bulgarian) and `/en` (English).

### Admin

- URL: `/admin`
- Seeded login: **admin@bcci.bg** / **password** — change it on first use.

## Content model

| Where | What |
|-------|------|
| **Съдържание на страници** (Pages) | Per-page copy (hero, prose, quotes) + repeaters for the pillars / education plans / survey audiences, plus the global header/footer/contact block. Config-driven from `config/site.php`. |
| **Новини** (News) | Full articles with cover image, excerpt, rich-text body and a `/news/{slug}` detail page. |
| **Становища** (Positions) | Title, date, scope (ЕС / Национално) and an optional PDF. |
| **Партньори** (Partners) | Logo, link, order. |
| **Екип** (Team) | Photo, name, translatable role, order. |
| **Съобщения** (Messages) | Read-only inbox of contact-form submissions. |

Translations are stored as JSON (`{bg, en}`) on array-cast columns; a blank EN
value falls back to BG so a page never renders empty.

## Internationalisation

- BG is the default locale (unprefixed URLs); EN lives under `/en`.
- Locale is resolved from the route-name prefix (`bg.about` / `en.about`) by
  `App\Http\Middleware\SetLocale`, and persisted in the session so Livewire
  interactions (e.g. the contact form) keep the right language.
- The language switcher maps the current page 1:1 to its counterpart.

## SEO / GEO

Handled by `App\Support\Seo` (rendered in the layout `<head>`) plus
`App\Http\Controllers\SeoController`:

- Per-page `<title>`, meta description, canonical, robots
- `hreflang` alternates (bg / en / x-default) on every page
- Open Graph + Twitter cards
- schema.org **JSON-LD `@graph`** — Organization, WebSite, WebPage,
  BreadcrumbList, NewsArticle, ItemList
- `/sitemap.xml` (with hreflang alternates), `/robots.txt`
- `/llms.txt` — machine-readable site map for answer-engines
- `/feed` and `/en/feed` — RSS for news

## Placeholder data to replace

The following are seeded as placeholders and should be updated in the admin
(under **Съдържание на страници → Общи настройки**, or the relevant page):

- Contact email / phone / address
- Newsletter (ИНФОБИЗНЕС) subscribe URL — currently links to `bcci.bg`
- Survey URL on the Survey page
- Sample news, positions, partners and team members

## Deferred (production hardening)

Intentionally out of scope for this phase; wire up before going live:

- Real email delivery for the contact form (submissions are stored in the DB now)
- Self-hosted fonts (currently Google Fonts CDN)
- Real deployment configuration

## Tests

```bash
php artisan test
```

Covers public rendering in both locales, the SEO endpoints, contact-form
persistence + validation, and — importantly — that the Filament content editor
**saves** correctly through the translatable + repeater fields.
