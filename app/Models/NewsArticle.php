<?php

namespace App\Models;

use App\Concerns\HasTranslations;
use App\Support\FeedBuilder;
use Filament\Forms\Components\RichEditor\FileAttachmentProviders\SpatieMediaLibraryFileAttachmentProvider;
use Filament\Forms\Components\RichEditor\Models\Concerns\InteractsWithRichContent;
use Filament\Forms\Components\RichEditor\Models\Contracts\HasRichContent;
use Illuminate\Database\Eloquent\Model;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class NewsArticle extends Model implements HasMedia, HasRichContent
{
    use HasTranslations;
    use InteractsWithMedia;
    use InteractsWithRichContent;

    /** The one collection an article has: its cover image. */
    public const COVER = 'cover';

    /**
     * Cover conversions, name => width in pixels. Heights follow the 3:2 the
     * layout reserves. These widths are also the `w` descriptors in the srcset,
     * so they have to be what the files actually measure — which is why the
     * conversions crop to an exact size rather than fitting within a bound.
     *
     * The ladder is 1:2:3 against the widths the layout actually renders:
     * 600 covers the 320px index card at 1x; 1200 covers that card at 2x, the
     * ~389px home cards at 2x and the ~704px article hero at 1x; 1800 covers
     * the hero at 2x. `news:covers` renders at 1800 so the largest is never an
     * upscale.
     */
    public const COVER_SIZES = ['card' => 600, 'wide' => 1200, 'hero' => 1800];

    /**
     * The conversion used for a plain `src`. Deliberately the middle rung, not
     * the largest: browsers that understand srcset ignore this attribute, so
     * the only clients that fetch it are the ones least able to afford 1800px.
     */
    public const COVER_SRC = 'wide';

    protected $fillable = [
        'slug', 'title', 'excerpt', 'body',
        'meta_title', 'meta_description', 'published_at', 'is_published',
    ];

    protected $casts = [
        'title' => 'array',
        'excerpt' => 'array',
        'body' => 'array',
        'meta_title' => 'array',
        'meta_description' => 'array',
        'published_at' => 'date',
        'is_published' => 'boolean',
    ];

    /** Publishing or editing an article invalidates the cached RSS feed. */
    protected static function booted(): void
    {
        static::saved(fn () => FeedBuilder::flush());
        static::deleted(fn () => FeedBuilder::flush());
    }

    public function scopePublished($query)
    {
        // `published_at` in the future means scheduled, not published — without
        // the date check a future-dated article leaks onto the site and into
        // the sitemap the moment it is saved.
        return $query->where('is_published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * One cover per article: a second upload replaces the first rather than
     * stacking behind it, which also makes re-seeding idempotent.
     */
    /**
     * Images dropped into the body text are stored by the media library too.
     *
     * One collection per locale, never a shared one: the provider's
     * cleanUpFileAttachments() clears everything in a collection except the
     * UUIDs referenced by its own attribute, so a shared collection would make
     * saving the Bulgarian tab delete every image used only in the English one.
     */
    public function setUpRichContent(): void
    {
        foreach (array_keys(config('site.locales', ['bg' => 'БГ'])) as $locale) {
            $this->registerRichContent("body.{$locale}")
                // The provider defaults attachments to private, which would
                // put them on a non-served disk and hand out temporary URLs.
                // Article illustrations are as public as the article is.
                ->fileAttachmentsDisk('public')
                ->fileAttachmentsVisibility('public')
                ->fileAttachmentProvider(
                    SpatieMediaLibraryFileAttachmentProvider::make()
                        ->collection(self::bodyAttachmentCollection($locale)),
                );
        }
    }

    public static function bodyAttachmentCollection(string $locale): string
    {
        return "body-{$locale}";
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(self::COVER)
            // Covers are public files, served straight from public/storage.
            // Pinning the disk here rather than leaving it to a default is what
            // keeps every writer agreeing: the app's own default disk is
            // `local` (rooted in storage/app/private, which is not web-served),
            // and Filament hands its default to the media library unless the
            // collection names one. Without this an upload through the admin
            // lands outside the served path and its URL 404s.
            ->useDisk('public')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp', 'image/avif']);

        // Body attachments: same public disk, but no singleFile and no
        // conversions — an article body may hold any number of images, and
        // they are inserted at whatever size the editor chose.
        foreach (array_keys(config('site.locales', ['bg' => 'БГ'])) as $locale) {
            $this->addMediaCollection(self::bodyAttachmentCollection($locale))
                ->useDisk('public');
        }
    }

    /**
     * Conversions run inline. `queue_conversions_by_default` is true and the
     * queue is the database driver, so left to the default these would sit
     * unprocessed until a worker happened to run — and the srcset would point
     * at files that do not exist.
     */
    public function registerMediaConversions(?Media $media = null): void
    {
        // Recorded by StoreMediaDimensions when the file was added, so reading
        // it costs nothing — this method runs on every URL the media builds.
        $sourceWidth = $media?->getCustomProperty('width');
        $coveredSource = false;

        foreach (self::COVER_SIZES as $name => $width) {
            // Walk the ladder until one rung covers the source, then stop.
            // That always reaches the file's native resolution — a 1152px
            // upload still gets the 1200 variant, four percent of upscale for
            // a sharp hero — while refusing the 1800 that would cost 38KB to
            // add nothing. Skipping is safe on its own: coverSrc() and
            // coverSrcset() both ask hasGeneratedConversion() first, so an
            // absent size drops out of the srcset rather than 404ing.
            if ($coveredSource) {
                continue;
            }

            if ($sourceWidth && $width >= $sourceWidth) {
                $coveredSource = true;
            }

            $this->addMediaConversion($name)
                ->performOnCollections(self::COVER)
                ->fit(Fit::Crop, $width, (int) round($width / 3 * 2))
                ->format('webp')
                // The optimizer chain shells out to jpegoptim/optipng/pngquant,
                // none of which are installed here, and on WebP that GD has
                // already encoded it measured 6 bytes *larger* for 0.07s a call.
                // Drop this line if those binaries are ever added to the image.
                ->nonOptimized()
                ->nonQueued();
        }
    }

    /**
     * The original file. Kept deliberately un-converted for og:image and
     * JSON-LD: social scrapers want a large PNG or JPEG, and several still do
     * not accept WebP.
     */
    public function imageUrl(): ?string
    {
        return $this->getFirstMediaUrl(self::COVER) ?: null;
    }

    /** Single URL for an `<img src>`, falling back to the original. */
    public function coverSrc(): ?string
    {
        $media = $this->getFirstMedia(self::COVER);

        if (! $media) {
            return null;
        }

        return $media->hasGeneratedConversion(self::COVER_SRC)
            ? $media->getUrl(self::COVER_SRC)
            : $media->getUrl();
    }

    /** `srcset` across the generated cover sizes, or null when there are none. */
    public function coverSrcset(): ?string
    {
        $media = $this->getFirstMedia(self::COVER);

        if (! $media) {
            return null;
        }

        $set = [];

        foreach (self::COVER_SIZES as $name => $width) {
            if ($media->hasGeneratedConversion($name)) {
                $set[] = $media->getUrl($name)." {$width}w";
            }
        }

        return $set ? implode(', ', $set) : null;
    }

    /** The cover as a Media record, for callers that need its dimensions. */
    public function cover(): ?Media
    {
        return $this->getFirstMedia(self::COVER);
    }
}
