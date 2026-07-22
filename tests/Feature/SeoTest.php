<?php

namespace Tests\Feature;

use Database\Seeders\SiteSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
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

        // First news card is above the fold; the rest may lazy-load.
        $news = $this->get('/news')->assertOk()->getContent();
        $this->assertStringContainsString('loading="eager"', $news);
    }
}
