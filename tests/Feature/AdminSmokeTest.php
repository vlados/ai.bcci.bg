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
}
