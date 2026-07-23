<?php

namespace Tests\Feature;

use App\Models\NewsArticle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\FileUploadConfiguration;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Tests\TestCase;

/**
 * Covers the dot-notation shim in App\Concerns\HasTranslations.
 *
 * Filament's rich content registers one attribute per editor and writes back
 * through that name, so `body.bg` has to behave like a column. These tests are
 * the contract that makes the rich editor's file attachments usable on a
 * translated field.
 */
class TranslatedAttributeTest extends TestCase
{
    use RefreshDatabase;

    protected function article(): NewsArticle
    {
        return NewsArticle::create([
            'slug' => 'dot-notation',
            'title' => ['bg' => 'Заглавие', 'en' => 'Title'],
            'body' => ['bg' => '<p>Текст</p>', 'en' => '<p>Body</p>'],
            'published_at' => '2026-07-20',
            'is_published' => true,
        ]);
    }

    public function test_dot_notation_writes_into_the_translated_column(): void
    {
        $article = $this->article();

        $article->setAttribute('body.bg', '<p>Нов текст</p>');
        $article->save();

        $fresh = $article->fresh();

        $this->assertSame('<p>Нов текст</p>', $fresh->body['bg']);
        $this->assertSame('<p>Body</p>', $fresh->body['en'], 'The other locale must survive the write');
    }

    public function test_dot_notation_reads_back_the_locale(): void
    {
        $article = $this->article();

        $this->assertSame('<p>Текст</p>', $article->getAttribute('body.bg'));
        $this->assertSame('<p>Body</p>', $article->getAttribute('body.en'));
    }

    public function test_a_locale_the_site_does_not_publish_is_not_treated_as_a_translation(): void
    {
        $article = $this->article();

        // 'fr' is not in site.locales, so this must fall through to Eloquent
        // rather than silently creating a translation.
        $this->assertNull($article->getAttribute('body.fr'));
    }

    public function test_ordinary_attributes_are_untouched(): void
    {
        $article = $this->article();

        $article->setAttribute('slug', 'still-plain');

        $this->assertSame('still-plain', $article->slug);
        $this->assertSame(['bg' => 'Заглавие', 'en' => 'Title'], $article->title);
    }

    public function test_an_uploaded_body_attachment_lands_in_its_locale_collection(): void
    {
        $article = $this->article();
        $provider = $article->getRichContentAttribute('body.bg')->getFileAttachmentProvider();

        // Livewire routes temporary uploads to its own disk under testing.
        $disk = FileUploadConfiguration::disk();
        Storage::fake($disk);

        $path = UploadedFile::fake()->image('inline.png', 400, 300)
            ->store(FileUploadConfiguration::directory(), ['disk' => $disk]);

        // createFromLivewire() re-prepends the temp directory, so it wants the
        // bare filename rather than the path store() hands back.
        $uuid = $provider->saveUploadedFileAttachment(
            TemporaryUploadedFile::createFromLivewire(basename($path)),
        );

        $media = $article->refresh()->getMedia(NewsArticle::bodyAttachmentCollection('bg'));

        $this->assertCount(1, $media);
        $this->assertSame($uuid, $media->first()->uuid);
        $this->assertSame('public', $media->first()->disk, 'Attachments must be web-served');
        $this->assertCount(0, $article->getMedia(NewsArticle::bodyAttachmentCollection('en')));

        // The cover collection must not have collected a body image.
        $this->assertCount(0, $article->getMedia(NewsArticle::COVER));
    }

    public function test_each_locale_gets_its_own_attachment_collection_on_the_public_disk(): void
    {
        $collections = collect((new NewsArticle)->getRegisteredMediaCollections())->keyBy('name');

        foreach (array_keys(config('site.locales')) as $locale) {
            $name = NewsArticle::bodyAttachmentCollection($locale);

            $this->assertArrayHasKey($name, $collections->all());
            $this->assertSame('public', $collections[$name]->diskName, "{$name} must be web-served");
        }

        // Sharing one collection across locales would make saving the Bulgarian
        // tab delete every image used only in the English body.
        $this->assertNotSame(
            NewsArticle::bodyAttachmentCollection('bg'),
            NewsArticle::bodyAttachmentCollection('en'),
        );
    }
}
