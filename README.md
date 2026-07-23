# Съвет за изкуствен интелект към БТПП (AI Council at BCCI)

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

## Going live

Run through this before the first production deploy. Every item here has bitten
this project at least once.

**1. Point the app at the canonical host.** `APP_URL=https://ai.bcci.bg` — it
drives every canonical, hreflang, Open Graph and JSON-LD URL and the generated
sitemap. `SEO_PRODUCTION_HOST=ai.bcci.bg` keeps every *other* host (dev box,
preview, bare IP) on `noindex`. Both are documented in `.env.example`.

**2. Generate the static SEO files** — `php artisan seo:generate`. `sitemap.xml`
and `llms.txt` are gitignored and have no route fallback, so until this runs
those URLs 404 while `robots.txt` advertises the sitemap.

**3. Cron the scheduler**, or nothing ever regenerates:

```
* * * * * cd /path/to/app && php artisan schedule:run >> /dev/null 2>&1
```

**4. Redirect the old URLs.** The `ai.bcci.bg` side is **already handled in the
app** — `routes/web.php` 301s `/ai-business-2026` and anything beneath it to
`/survey` in a single hop, preserving query strings so campaign tracking
survives. Covered by `SeoTest::test_legacy_campaign_urls_redirect_in_one_hop`.

Two things still need doing at the server:

```
ai.bcci.bg/   →  remove the existing 302 to /ai-business-2026,
                 so the root serves the new homepage
prouchvane.bg →  leave alone for now (see the warning below)
```

⚠️ **Do not redirect `prouchvane.bg/ai-business-2026` to `ai.bcci.bg`.** That is
where the live questionnaire runs, and the survey page's CTA points at it. Add
that redirect and you close a loop — `/survey` → `prouchvane.bg` →
`ai.bcci.bg/ai-business-2026` → `/survey` — which traps every visitor trying to
take the survey. Consolidate that domain only after the questionnaire itself has
moved onto this site, and change the CTA in the same deploy.

Its root is a separate survey platform, not a mirror, so it must never be
blanket-redirected regardless.

**5. Protect the old dev host.** `aicouncil.hosting.vladko.dev` served
`index, follow` with a self-canonical. Item 1 fixes that in the app, but put it
behind HTTP auth as well, and 301 it to the production equivalents afterwards.

**6. Decide the AI-crawler policy.** GPTBot and OAI-SearchBot are currently
allowed by omission. That is a defensible choice given the GEO goals — but it
should be a decision, not an accident (SEO.md line 1230).

### What is deliberately empty

Team, partners and positions ship empty rather than populated with placeholders.
Their sections hide themselves until real records exist; add them in the admin
panel. Positions additionally require an attached document — see
`Position::scopePublished()`.

### How each one is served

`/sitemap.xml` and `/llms.txt` are **not** routes. `App\Support\SitemapBuilder`
(spatie/laravel-sitemap) and `App\Support\LlmsTxtBuilder` write them into `public/`,
so the web server hands over a static file without booting Laravel or touching the
database. They're the expensive ones — the sitemap covers every page × every locale
plus all published news, and llms.txt reads a row per content page.

`php artisan seo:generate` writes both; the scheduler runs it nightly at 03:30. That
means the box needs the Laravel scheduler cronned:

```
* * * * * cd /path/to/app && php artisan schedule:run >> /dev/null 2>&1
```

…and **deploys must run `php artisan seo:generate`**, since both files are gitignored
and there's no route to fall back on — until the first run those URLs 404. Run it by
hand after a large content edit rather than waiting for the nightly job.

`/feed` and `/en/feed` stay live routes, cached instead of generated: a feed's value is
freshness, and the URLs have no extension to serve statically (and are subscribed to,
so they can't change). `App\Support\FeedBuilder` caches the rendered XML per locale
forever, and `NewsArticle` / `Page` drop that cache on save — fresher than a nightly
file, cheaper than rendering per request.

`/robots.txt` remains a route (it's trivial to build). Note there must be **no**
`public/robots.txt` — a static file there shadows the route and the `Sitemap:`
directive silently stops being served.

## Placeholder data to replace

The following are seeded as placeholders and should be updated in the admin
(under **Съдържание на страници → Общи настройки**, or the relevant page):

- Contact email / phone / address
- Newsletter (ИНФОБИЗНЕС) subscribe URL — currently links to `bcci.bg`
- Survey URL on the Survey page

Team, partners and positions are no longer seeded with placeholders — they ship
empty and their sections stay hidden until you add real records.

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
