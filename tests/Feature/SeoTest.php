<?php

namespace Tests\Feature;

use Database\Seeders\SiteSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Middleware\TrustHosts;
use Tests\TestCase;

/**
 * Regression tests for the SEO defects found in the July 2026 audit.
 *
 * Each one here failed in production at the time it was written; they exist so
 * that a plausible-looking edit cannot quietly reintroduce the defect.
 */
class SeoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(SiteSeeder::class);
    }

    public function test_only_the_production_host_is_indexable(): void
    {
        config(['site.seo.production_host' => 'ai.bcci.bg']);

        $this->get('http://ai.bcci.bg/about')
            ->assertOk()
            ->assertSee('<meta name="robots" content="index, follow', false);

        // A dev host, a preview build or a bare IP must never be indexable —
        // otherwise it competes with production for production's own content.
        $this->get('http://aicouncil.hosting.vladko.dev/about')
            ->assertOk()
            ->assertSee('<meta name="robots" content="noindex, nofollow">', false);
    }

    /**
     * robots.txt matching is a plain prefix match, so `Disallow: /livewire`
     * also matched Livewire's hashed asset path (/livewire-<hash>/livewire.js)
     * and blocked the site's own JavaScript from every crawler.
     */
    public function test_robots_does_not_block_the_livewire_runtime(): void
    {
        $robots = $this->get('/robots.txt')->assertOk()->getContent();

        $this->assertStringNotContainsString('Disallow: /livewire', $robots);
        $this->assertStringContainsString('Disallow: /admin', $robots);

        // The health endpoint renders the site's own <title>, so left indexable
        // it competes on the brand query with a page that says "Application up".
        $this->assertStringContainsString('Disallow: /up', $robots);
    }

    /** BCCI is a chamber under private law, not a state body. */
    public function test_organization_is_not_marked_up_as_a_government_body(): void
    {
        $html = $this->get('/')->assertOk()->getContent();

        $this->assertStringNotContainsString('GovernmentOrganization', $html);
        $this->assertStringContainsString('"@type":"Organization"', $html);
    }

    public function test_contact_form_fields_have_real_labels(): void
    {
        $html = $this->get('/contacts')->assertOk()->getContent();

        foreach (['contact-name', 'contact-email', 'contact-message'] as $id) {
            $this->assertStringContainsString('for="'.$id.'"', $html);
            $this->assertStringContainsString('id="'.$id.'"', $html);
        }

        $this->assertStringContainsString('autocomplete="name"', $html);
        $this->assertStringContainsString('autocomplete="email"', $html);
    }

    /**
     * The audit found BreadcrumbList markup with no visible trail. Structured
     * data is only allowed to describe what the page actually shows.
     */
    public function test_breadcrumbs_are_visible_on_interior_pages_only(): void
    {
        $article = $this->get('/news/ai-adoption-gap-bulgaria-eu-eurostat-2025')->assertOk()->getContent();
        $this->assertStringContainsString('aria-current="page"', $article);
        $this->assertStringContainsString('BreadcrumbList', $article);

        $en = $this->get('/en/news/ai-adoption-gap-bulgaria-eu-eurostat-2025')->assertOk()->getContent();
        $this->assertStringContainsString('News', $en);

        // The homepage trail is a single item, which describes no path at all —
        // neither the visible trail nor the markup should appear.
        $home = $this->get('/')->assertOk()->getContent();
        $this->assertStringNotContainsString('BreadcrumbList', $home);
    }

    /**
     * The campaign URLs ai.bcci.bg served before this site replaced it. They
     * carry real external links, so they must resolve in one permanent hop
     * and keep their tracking parameters.
     */
    public function test_legacy_campaign_urls_redirect_in_one_hop(): void
    {
        foreach (['/ai-business-2026', '/ai-business-2026/survey', '/ai-business-2026/deeper/path'] as $old) {
            $this->get($old)->assertRedirect(url('/survey'))->assertStatus(301);
        }

        // Campaign attribution must survive the redirect.
        $this->get('/ai-business-2026?utm_source=linkedin&utm_campaign=ai2026')
            ->assertStatus(301)
            ->assertRedirect(url('/survey').'?utm_source=linkedin&utm_campaign=ai2026');

        // And the destination must be a real page, not another redirect.
        $this->get('/survey')->assertOk();
    }

    /**
     * Host pinning must never lock a deployment out of its own domain.
     *
     * Laravel enforces trusted hosts outside local/testing, so trusting only
     * the production host would make the staging deployment answer 400 on the
     * domain it is actually served from.
     */
    public function test_trusted_hosts_include_this_deployments_own_domain(): void
    {
        config([
            'app.url' => 'https://aicouncil.hosting.vladko.dev',
            'site.seo.production_host' => 'ai.bcci.bg',
        ]);

        $middleware = new TrustHosts(app());
        $hosts = (new \ReflectionMethod($middleware, 'hosts'))->invoke($middleware);

        $this->assertContains('aicouncil.hosting.vladko.dev', $hosts, 'A deployment must trust its own APP_URL host.');
        $this->assertContains('ai.bcci.bg', $hosts, 'The canonical production host must stay trusted.');
    }

    /** The LCP image must never be lazy-loaded (SEO.md:1048). */
    public function test_above_the_fold_imagery_is_eager(): void
    {
        $home = $this->get('/')->assertOk()->getContent();

        if (str_contains($home, 'fetchpriority="high"')) {
            $this->assertMatchesRegularExpression(
                '/<img[^>]*fetchpriority="high"(?![^>]*loading="lazy")/',
                $home,
                'The priority hero image must not also be lazy-loaded.'
            );
        }

        // First news card is above the fold; the rest may lazy-load. Only
        // asserted when a cover image actually exists — the data-journalism
        // articles deliberately ship without stock photography.
        $news = $this->get('/news')->assertOk()->getContent();
        if (str_contains($news, '<img data-morph')) {
            $this->assertMatchesRegularExpression(
                '/<img data-morph[^>]*loading="eager"/',
                $news,
                'The first news card is above the fold and must not be lazy-loaded.'
            );
        }
    }
}
