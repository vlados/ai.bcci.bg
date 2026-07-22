<?php

namespace Tests\Feature;

use App\Livewire\Pages\Contacts;
use App\Models\ContactMessage;
use Database\Seeders\SiteSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class FrontendTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(SiteSeeder::class);
    }

    public function test_home_renders_in_both_locales(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Изкуственият интелект отдавна не е бъдещето')
            ->assertSee('application/ld+json', false)
            ->assertSee('hreflang', false);

        $this->get('/en')
            ->assertOk()
            ->assertSee('Artificial intelligence is no longer the future');
    }

    public function test_all_public_pages_load(): void
    {
        foreach (['about', 'education', 'positions', 'survey', 'partners', 'news', 'contacts'] as $slug) {
            $this->get("/{$slug}")->assertOk();
            $this->get("/en/{$slug}")->assertOk();
        }
    }

    public function test_news_detail_and_feed_and_sitemap(): void
    {
        $this->get('/news/stanovishte-ai-act')->assertOk()->assertSee('NewsArticle', false);
        $this->get('/feed')->assertOk()->assertHeader('content-type', 'application/rss+xml; charset=UTF-8');
        $this->get('/sitemap.xml')->assertOk()->assertSee('hreflang', false);
        $this->get('/robots.txt')->assertOk()->assertSee('Sitemap:');
        $this->get('/llms.txt')->assertOk()->assertSee('# Съвет по изкуствен интелект към БТПП');
    }

    public function test_contact_form_persists_message(): void
    {
        app()->setLocale('bg');

        Livewire::test(Contacts::class)
            ->set('name', 'Иван Петров')
            ->set('email', 'ivan@example.com')
            ->set('message', 'Здравейте, имам въпрос.')
            ->call('submit')
            ->assertSet('sent', true)
            ->assertHasNoErrors();

        $this->assertDatabaseHas(ContactMessage::class, [
            'email' => 'ivan@example.com',
            'name' => 'Иван Петров',
            'locale' => 'bg',
        ]);
    }

    public function test_contact_form_validates(): void
    {
        Livewire::test(Contacts::class)
            ->set('name', '')
            ->set('email', 'not-an-email')
            ->set('message', '')
            ->call('submit')
            ->assertHasErrors(['name', 'email', 'message']);

        $this->assertDatabaseCount(ContactMessage::class, 0);
    }
}
