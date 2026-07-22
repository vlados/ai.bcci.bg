<?php

namespace App\Support;

use App\Models\Page;

/**
 * Request-scoped SEO/GEO builder.
 *
 * Each full-page Livewire component populates this in its render() (which runs
 * before the layout renders its <head>), then the layout echoes ->render().
 * Produces: title, description, canonical, robots, hreflang alternates,
 * Open Graph, Twitter cards, and a schema.org JSON-LD @graph
 * (Organization + WebSite + WebPage + BreadcrumbList + page-specific nodes) —
 * the structured, machine-readable signals that both classic search and
 * generative answer-engines (GEO) rely on.
 */
class Seo
{
    protected ?string $title = null;

    protected bool $appendBrand = true;

    protected ?string $description = null;

    protected ?string $canonical = null;

    protected ?string $image = null;

    protected string $type = 'website';

    protected bool $noindex = false;

    protected ?string $publishedAt = null;

    protected ?string $modifiedAt = null;

    protected array $breadcrumbs = [];

    protected array $extraJsonLd = [];

    protected ?Page $global = null;

    public function title(string $title, bool $appendBrand = true): static
    {
        $this->title = $title;
        $this->appendBrand = $appendBrand;

        return $this;
    }

    public function description(?string $description): static
    {
        $this->description = $description ? trim(preg_replace('/\s+/', ' ', strip_tags($description))) : null;

        return $this;
    }

    public function canonical(?string $url): static
    {
        $this->canonical = $url;

        return $this;
    }

    public function image(?string $path): static
    {
        $this->image = $path ? (str_starts_with($path, 'http') ? $path : asset($path)) : null;

        return $this;
    }

    public function type(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function noindex(bool $value = true): static
    {
        $this->noindex = $value;

        return $this;
    }

    public function article(?string $publishedAt, ?string $modifiedAt = null): static
    {
        $this->type = 'article';
        $this->publishedAt = $publishedAt;
        $this->modifiedAt = $modifiedAt ?? $publishedAt;

        return $this;
    }

    /** @param array<int,array{name:string,url:?string}> $items */
    public function breadcrumbs(array $items): static
    {
        $this->breadcrumbs = $items;

        return $this;
    }

    public function addJsonLd(array $node): static
    {
        $this->extraJsonLd[] = $node;

        return $this;
    }

    /**
     * Is this request being served from the one host allowed to be indexed?
     *
     * Any other host — the dev box, a preview build, a bare IP, a www variant —
     * serves `noindex` instead, so a staging copy can never be indexed as, or
     * compete with, the real site. Note this deliberately does NOT also block
     * crawling via robots.txt: a crawler has to be able to fetch the page in
     * order to see the noindex, and a robots exclusion alone would still let
     * the bare URL surface in results.
     *
     * A blank `production_host` disables the check, which is an explicit opt-out.
     */
    protected function onProductionHost(): bool
    {
        $production = config('site.seo.production_host');

        return blank($production) || request()->getHost() === $production;
    }

    // ---- rendering -------------------------------------------------------

    public function render(): string
    {
        $locale = app()->getLocale();
        $default = config('site.default_locale', 'bg');
        $brand = $this->localized(config('site.org.short'));
        $orgName = $this->localized(config('site.org.name'));

        $title = $this->title ?? $orgName;
        if ($this->appendBrand && ! str_contains($title, $brand)) {
            $title = $title.' — '.$brand;
        }

        $description = $this->description
            ?? $this->globalDescription()
            ?? $orgName;

        $canonical = $this->canonical ?? request()->url();
        $image = $this->image ?? asset(config('site.seo.og_image'));
        $ogLocale = $locale === 'bg' ? 'bg_BG' : 'en_US';
        $altLocale = $locale === 'bg' ? 'en_US' : 'bg_BG';

        $out = [];
        $out[] = '<title>'.e($title).'</title>';
        $out[] = '<meta name="description" content="'.e($description).'">';
        $out[] = '<link rel="canonical" href="'.e($canonical).'">';
        $out[] = ($this->noindex || ! $this->onProductionHost())
            ? '<meta name="robots" content="noindex, nofollow">'
            : '<meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">';

        // hreflang alternates (bg / en / x-default → default locale)
        foreach (SiteNav::localeAlternates() as $alt) {
            $out[] = '<link rel="alternate" hreflang="'.e($alt['locale']).'" href="'.e($alt['url']).'">';
            if ($alt['locale'] === $default) {
                $out[] = '<link rel="alternate" hreflang="x-default" href="'.e($alt['url']).'">';
            }
        }

        // Open Graph
        $out[] = '<meta property="og:type" content="'.e($this->type).'">';
        $out[] = '<meta property="og:site_name" content="'.e($orgName).'">';
        $out[] = '<meta property="og:title" content="'.e($title).'">';
        $out[] = '<meta property="og:description" content="'.e($description).'">';
        $out[] = '<meta property="og:url" content="'.e($canonical).'">';
        $out[] = '<meta property="og:image" content="'.e($image).'">';
        $out[] = '<meta property="og:locale" content="'.e($ogLocale).'">';
        $out[] = '<meta property="og:locale:alternate" content="'.e($altLocale).'">';
        if ($this->type === 'article') {
            if ($this->publishedAt) {
                $out[] = '<meta property="article:published_time" content="'.e($this->publishedAt).'">';
            }
            if ($this->modifiedAt) {
                $out[] = '<meta property="article:modified_time" content="'.e($this->modifiedAt).'">';
            }
        }

        // Twitter
        $out[] = '<meta name="twitter:card" content="summary_large_image">';
        $out[] = '<meta name="twitter:title" content="'.e($title).'">';
        $out[] = '<meta name="twitter:description" content="'.e($description).'">';
        $out[] = '<meta name="twitter:image" content="'.e($image).'">';

        // JSON-LD @graph
        $out[] = '<script type="application/ld+json">'
            .json_encode($this->graph($title, $description, $canonical, $image), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
            .'</script>';

        return implode("\n    ", $out);
    }

    protected function graph(string $title, string $description, string $canonical, string $image): array
    {
        $locale = app()->getLocale();
        $base = rtrim(url('/'), '/');
        $orgId = $base.'/#organization';
        $siteId = $base.'/#website';

        $organization = array_filter([
            '@type' => config('site.org.legal_type', 'Organization'),
            '@id' => $orgId,
            'name' => $this->localized(config('site.org.name')),
            'alternateName' => $this->localized(config('site.org.short')),
            'url' => $base,
            'logo' => asset('assets/logo.png'),
            'image' => $image,
            'description' => $this->globalDescription(),
            'foundingDate' => config('site.org.founding_date'),
            'areaServed' => $this->localized(config('site.org.area_served')),
            'knowsAbout' => array_map(fn ($t) => $this->localized($t), config('site.org.knows_about', [])),
            'sameAs' => config('site.org.same_as', []),
            'parentOrganization' => [
                '@type' => 'Organization',
                'name' => $this->localized(config('site.org.parent.name')),
                'url' => config('site.org.parent.url'),
            ],
            'contactPoint' => $this->contactPoint(),
        ]);

        $website = [
            '@type' => 'WebSite',
            '@id' => $siteId,
            'url' => $base,
            'name' => $this->localized(config('site.org.name')),
            'inLanguage' => $locale,
            'publisher' => ['@id' => $orgId],
        ];

        $webpage = array_filter([
            '@type' => $this->type === 'article' ? 'WebPage' : 'WebPage',
            '@id' => $canonical.'#webpage',
            'url' => $canonical,
            'name' => $title,
            'description' => $description,
            'inLanguage' => $locale,
            'isPartOf' => ['@id' => $siteId],
            'about' => ['@id' => $orgId],
            'primaryImageOfPage' => $image,
            'datePublished' => $this->publishedAt,
            'dateModified' => $this->modifiedAt,
        ]);

        $graph = [$organization, $website, $webpage];

        if (! empty($this->breadcrumbs)) {
            $graph[] = $this->breadcrumbList($canonical);
        }

        foreach ($this->extraJsonLd as $node) {
            $graph[] = $node;
        }

        return [
            '@context' => 'https://schema.org',
            '@graph' => array_values($graph),
        ];
    }

    protected function breadcrumbList(string $canonical): array
    {
        $items = [];
        foreach ($this->breadcrumbs as $i => $crumb) {
            $items[] = array_filter([
                '@type' => 'ListItem',
                'position' => $i + 1,
                'name' => $crumb['name'],
                'item' => $crumb['url'] ?? null,
            ]);
        }

        return [
            '@type' => 'BreadcrumbList',
            '@id' => $canonical.'#breadcrumb',
            'itemListElement' => $items,
        ];
    }

    protected function contactPoint(): ?array
    {
        $email = $this->global()->get('contact_email');
        $phone = $this->global()->get('contact_phone');

        if (! $email && ! $phone) {
            return null;
        }

        return array_filter([
            '@type' => 'ContactPoint',
            'contactType' => 'information',
            'email' => $email,
            'telephone' => $phone,
            'areaServed' => 'BG',
            'availableLanguage' => ['Bulgarian', 'English'],
        ]);
    }

    protected function globalDescription(): ?string
    {
        $val = $this->global()->get('seo_description');

        return $val ?: null;
    }

    protected function global(): Page
    {
        return $this->global ??= Page::forKey('global');
    }

    protected function localized(mixed $value): string
    {
        if (is_array($value)) {
            return (string) ($value[app()->getLocale()] ?? $value[config('site.default_locale', 'bg')] ?? reset($value) ?? '');
        }

        return (string) ($value ?? '');
    }
}
