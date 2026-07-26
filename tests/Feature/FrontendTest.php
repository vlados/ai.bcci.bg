<?php

namespace Tests\Feature;

use App\Livewire\Pages\Contacts;
use App\Mail\ContactMessageReceived;
use App\Models\ContactMessage;
use App\Models\NewsArticle;
use App\Models\Page;
use App\Support\LlmsTxtBuilder;
use App\Support\SitemapBuilder;
use Database\Seeders\SiteSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
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

    public function test_news_detail_and_feed(): void
    {
        $this->get('/news/ai-adoption-gap-bulgaria-eu-eurostat-2025')->assertOk()->assertSee('NewsArticle', false);
        $this->get('/feed')->assertOk()->assertHeader('content-type', 'application/rss+xml; charset=UTF-8');
        $this->get('/robots.txt')->assertOk()->assertSee('Sitemap: '.url('/sitemap.xml'));
    }

    /** llms.txt is a generated file too — assert on what the builder produces. */
    public function test_llms_txt_lists_the_org_and_its_pages(): void
    {
        $md = (new LlmsTxtBuilder)->build();

        $this->assertStringContainsString('# Съвет за изкуствен интелект към Българската търговско-промишлена палата', $md);
        $this->assertStringContainsString(url('/about'), $md);
        $this->assertStringContainsString('ai-adoption-gap-bulgaria-eu-eurostat-2025', $md);
    }

    public function test_feed_is_cached_and_flushed_when_an_article_changes(): void
    {
        $first = $this->get('/feed')->assertOk()->getContent();

        // A direct DB write bypasses the model events, so the cache must still serve.
        DB::table('news_articles')->where('slug', 'ai-adoption-gap-bulgaria-eu-eurostat-2025')->update(['is_published' => false]);
        $this->assertSame($first, $this->get('/feed')->getContent());

        // Going through the model fires `saved`, which drops the cache.
        $article = NewsArticle::where('slug', 'ai-adoption-gap-bulgaria-eu-eurostat-2025')->first();
        $article->update(['is_published' => false]);

        $this->assertStringNotContainsString('ai-adoption-gap-bulgaria-eu-eurostat-2025', $this->get('/feed')->getContent());
    }

    /** The sitemap is a generated file, not a route — assert on what it renders. */
    public function test_sitemap_covers_every_page_in_both_locales(): void
    {
        $xml = (new SitemapBuilder)->build()->render();

        $this->assertStringContainsString('hreflang="bg"', $xml);
        $this->assertStringContainsString('hreflang="en"', $xml);
        $this->assertStringContainsString('hreflang="x-default"', $xml);

        foreach (['about', 'education', 'positions', 'survey', 'partners', 'news', 'contacts'] as $slug) {
            $this->assertStringContainsString('<loc>'.url("/{$slug}").'</loc>', $xml);
            $this->assertStringContainsString('<loc>'.url("/en/{$slug}").'</loc>', $xml);
        }

        $this->assertStringContainsString('<loc>'.url('/news/ai-adoption-gap-bulgaria-eu-eurostat-2025').'</loc>', $xml);
    }

    public function test_seo_generate_command_writes_both_files(): void
    {
        $paths = [
            public_path('sitemap.xml') => '<urlset',
            public_path('llms.txt') => '# Съвет за изкуствен интелект към Българската търговско-промишлена палата',
        ];
        $backups = [];
        foreach (array_keys($paths) as $path) {
            $backups[$path] = is_file($path) ? file_get_contents($path) : null;
        }

        try {
            $this->artisan('seo:generate')->assertSuccessful();

            foreach ($paths as $path => $needle) {
                $this->assertFileExists($path);
                $this->assertStringContainsString($needle, file_get_contents($path));
            }
        } finally {
            foreach ($backups as $path => $backup) {
                $backup === null ? @unlink($path) : file_put_contents($path, $backup);
            }
        }
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

    /**
     * The site's locale and fallback are both bg and there is no bg validation
     * lang file, so without explicit messages the visitor reads the raw key
     * "validation.required" under the field.
     */
    public function test_contact_form_errors_are_human_readable_in_both_locales(): void
    {
        foreach (['bg' => 'Моля, въведете вашето име.', 'en' => 'Please enter your name.'] as $locale => $expected) {
            app()->setLocale($locale);

            $errors = Livewire::test(Contacts::class)
                ->set('name', '')
                ->set('email', 'still@not.valid.enough')
                ->set('message', '')
                ->call('submit')
                ->errors();

            $this->assertSame($expected, $errors->first('name'), "wrong message for [$locale]");
            $this->assertStringNotContainsString('validation.', $errors->first('message'));
        }
    }

    public function test_contact_form_emails_the_council(): void
    {
        Mail::fake();
        app()->setLocale('bg');

        Livewire::test(Contacts::class)
            ->set('name', 'Иван Петров')
            ->set('email', 'ivan@example.com')
            ->set('message', 'Здравейте, имам въпрос.')
            ->call('submit')
            ->assertHasNoErrors();

        $recipient = Page::forKey('global')->get('contact_email');
        $this->assertNotEmpty($recipient, 'the seeded global page should carry a contact address');

        Mail::assertSent(ContactMessageReceived::class, function (ContactMessageReceived $mail) use ($recipient) {
            $rendered = $mail->render();

            return $mail->hasTo($recipient)
                && $mail->hasReplyTo('ivan@example.com')
                && str_contains($rendered, 'Здравейте, имам въпрос.')
                && str_contains($rendered, 'ivan@example.com');
        });
    }

    /** A mailer that is down must not lose a message the visitor already sent. */
    public function test_contact_message_survives_a_failing_mailer(): void
    {
        // ->once() is the point: it proves a send was actually attempted rather
        // than skipped by the empty-recipient guard, which would otherwise let
        // this test pass while no mail is ever sent.
        Mail::shouldReceive('to')->once()->andThrow(new \RuntimeException('SMTP is down'));

        Livewire::test(Contacts::class)
            ->set('name', 'Иван Петров')
            ->set('email', 'ivan@example.com')
            ->set('message', 'Здравейте, имам въпрос.')
            ->call('submit')
            ->assertSet('sent', true)
            ->assertHasNoErrors();

        $this->assertDatabaseHas(ContactMessage::class, ['email' => 'ivan@example.com']);
    }
}
