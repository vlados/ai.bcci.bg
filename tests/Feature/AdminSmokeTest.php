<?php

namespace Tests\Feature;

use App\Filament\Admin\Resources\NewsArticles\Pages\CreateNewsArticle;
use App\Filament\Admin\Resources\Pages\Pages\EditPage;
use App\Models\NewsArticle;
use App\Models\Page;
use App\Models\Position;
use App\Models\User;
use Database\Seeders\SiteSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class AdminSmokeTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(SiteSeeder::class);
        $this->admin = User::where('email', 'admin@bcci.bg')->firstOrFail();
    }

    public function test_resource_index_pages_render(): void
    {
        $this->actingAs($this->admin);

        foreach ([
            '/admin/pages',
            '/admin/news-articles',
            '/admin/positions',
            '/admin/partners',
            '/admin/team-members',
            '/admin/contact-messages',
        ] as $url) {
            $this->get($url)->assertOk();
        }
    }

    public function test_config_driven_page_editor_renders_for_every_page(): void
    {
        $this->actingAs($this->admin);

        foreach (Page::all() as $page) {
            $this->get("/admin/pages/{$page->getKey()}/edit")->assertOk();
        }
    }

    public function test_collection_forms_render(): void
    {
        $this->actingAs($this->admin);

        $urls = [
            '/admin/news-articles/create',
            '/admin/positions/create',
            '/admin/partners/create',
            '/admin/team-members/create',
            '/admin/news-articles/'.NewsArticle::first()->getRouteKey().'/edit',
            '/admin/positions/'.Position::first()->getRouteKey().'/edit',
        ];

        foreach ($urls as $url) {
            $this->get($url)->assertOk("Failed rendering: {$url}");
        }
    }

    public function test_page_editor_saves_content_including_repeater(): void
    {
        $this->actingAs($this->admin);

        $home = Page::where('key', 'home')->firstOrFail();
        $this->assertCount(3, $home->content['pillars']);

        $content = $home->content;
        $content['hero_title']['bg'] = 'НОВО ЗАГЛАВИЕ';
        $content['pillars'][0]['title']['bg'] = 'Променено направление';
        $content['pillars'][] = ['num' => '04', 'title' => ['bg' => 'Ново', 'en' => 'New'], 'text' => ['bg' => 'текст', 'en' => 'text']];

        Livewire::test(EditPage::class, ['record' => $home->getRouteKey()])
            ->fillForm(['content' => $content])
            ->call('save')
            ->assertHasNoFormErrors();

        $fresh = Page::where('key', 'home')->firstOrFail();

        // Edited translatable scalar persisted; the untouched EN side survived.
        $this->assertSame('НОВО ЗАГЛАВИЕ', $fresh->content['hero_title']['bg']);
        $this->assertSame($home->content['hero_title']['en'], $fresh->content['hero_title']['en']);

        // Repeater round-tripped: edited row + added row, nested translations intact.
        $this->assertCount(4, $fresh->content['pillars']);
        $this->assertSame('Променено направление', $fresh->content['pillars'][0]['title']['bg']);
        $this->assertSame('New', $fresh->content['pillars'][3]['title']['en']);

        // A content key not in the edited section must not be clobbered.
        $this->assertSame($home->content['quote_text']['bg'], $fresh->content['quote_text']['bg']);
    }

    public function test_news_create_persists_per_locale_translations(): void
    {
        $this->actingAs($this->admin);

        Livewire::test(CreateNewsArticle::class)
            ->fillForm([
                'slug' => 'test-article',
                'published_at' => '2026-07-20',
                'is_published' => true,
                'title' => ['bg' => 'Тестова новина', 'en' => 'Test article'],
                'excerpt' => ['bg' => 'Кратко резюме', 'en' => 'Short excerpt'],
                'body' => ['bg' => '<p>Съдържание</p>', 'en' => '<p>Content</p>'],
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $article = NewsArticle::where('slug', 'test-article')->firstOrFail();
        $this->assertSame('Тестова новина', $article->title['bg']);
        $this->assertSame('Test article', $article->title['en']);
        $this->assertStringContainsString('Съдържание', $article->body['bg']);
    }

    public function test_seeded_articles_carry_the_committed_cover(): void
    {
        $articles = NewsArticle::all();
        $this->assertNotEmpty($articles);

        foreach ($articles as $article) {
            $this->assertNotNull($article->imageUrl(), "No cover attached for {$article->slug}");
        }
    }

    public function test_news_form_uploads_a_cover_into_the_media_library(): void
    {
        $this->actingAs($this->admin);

        Livewire::test(CreateNewsArticle::class)
            ->fillForm([
                'slug' => 'article-with-cover',
                'published_at' => '2026-07-20',
                'is_published' => true,
                'title' => ['bg' => 'С корица', 'en' => 'With a cover'],
                'cover' => [UploadedFile::fake()->image('cover.png', 1200, 800)],
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $article = NewsArticle::where('slug', 'article-with-cover')->firstOrFail();

        $this->assertCount(1, $article->getMedia(NewsArticle::COVER));
        $this->assertNotNull($article->imageUrl());

        // The disk matters as much as the row: the app's default disk is
        // `local`, which is not web-served, and Filament will hand its own
        // default to the media library unless the collection pins one. A cover
        // written there exists in the database and 404s in the browser.
        $media = $article->getFirstMedia(NewsArticle::COVER);
        $this->assertSame('public', $media->disk);
        Storage::disk('public')->assertExists("{$media->id}/{$media->file_name}");
    }

    public function test_a_full_size_cover_generates_every_conversion(): void
    {
        $media = NewsArticle::firstOrFail()->getFirstMedia(NewsArticle::COVER);

        $this->assertSame(1800, $media->getCustomProperty('width'));

        foreach (array_keys(NewsArticle::COVER_SIZES) as $name) {
            $this->assertTrue($media->hasGeneratedConversion($name), "Missing conversion: {$name}");
        }
    }

    /**
     * Conversions walk the ladder until one rung covers the source and then
     * stop, so an undersized upload is never blown up to 1800px.
     */
    public function test_conversions_stop_at_the_first_size_covering_the_source(): void
    {
        $this->actingAs($this->admin);

        Livewire::test(CreateNewsArticle::class)
            ->fillForm([
                'slug' => 'small-cover',
                'published_at' => '2026-07-20',
                'is_published' => true,
                'title' => ['bg' => 'Малка корица', 'en' => 'Small cover'],
                'cover' => [UploadedFile::fake()->image('small.png', 900, 600)],
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $article = NewsArticle::where('slug', 'small-cover')->firstOrFail();
        $media = $article->getFirstMedia(NewsArticle::COVER);

        $this->assertSame(900, $media->getCustomProperty('width'));
        $this->assertTrue($media->hasGeneratedConversion('card'), '600 is below the 900px source');
        $this->assertTrue($media->hasGeneratedConversion('wide'), '1200 is the rung that covers 900px');
        $this->assertFalse($media->hasGeneratedConversion('hero'), '1800 would be pure upscale');

        // The srcset must advertise only what exists, with honest descriptors.
        $this->assertStringContainsString('600w', $article->coverSrcset());
        $this->assertStringContainsString('1200w', $article->coverSrcset());
        $this->assertStringNotContainsString('1800w', $article->coverSrcset());
    }

    /**
     * The `cover` collection is `singleFile`, which is what makes re-seeding
     * idempotent rather than stacking a new cover behind the old one.
     */
    public function test_a_second_cover_replaces_the_first(): void
    {
        $article = NewsArticle::firstOrFail();
        $source = public_path("assets/news/{$article->slug}.png");
        $this->assertFileExists($source);

        $article->addMedia($source)->preservingOriginal()->toMediaCollection(NewsArticle::COVER);
        $article->addMedia($source)->preservingOriginal()->toMediaCollection(NewsArticle::COVER);

        $this->assertCount(1, $article->refresh()->getMedia(NewsArticle::COVER));
    }
}
