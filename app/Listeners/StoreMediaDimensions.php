<?php

namespace App\Listeners;

use Spatie\MediaLibrary\MediaCollections\Events\MediaHasBeenAddedEvent;

/**
 * Records the pixel dimensions of an image on its media record.
 *
 * The media library does not track them, but conversions need the source width
 * so they can refuse to generate a variant larger than the file it came from.
 * Reading the file here is what keeps that check free afterwards: this event
 * fires once, after the upload has landed on disk but before conversions run
 * (see Filesystem::add), so registerMediaConversions() — which is called every
 * time a URL is built — can read a custom property instead of the filesystem.
 */
class StoreMediaDimensions
{
    public function handle(MediaHasBeenAddedEvent $event): void
    {
        $media = $event->media;

        if (! str_starts_with((string) $media->mime_type, 'image/')) {
            return;
        }

        $path = $media->getPath();

        // Only a local disk exposes a readable path. Anywhere else the property
        // stays unset, and every conversion is generated as it was before.
        if (! is_file($path)) {
            return;
        }

        // Fails on SVG and on anything malformed; leaving the property unset is
        // the right outcome in both cases.
        $size = @getimagesize($path);

        if ($size === false) {
            return;
        }

        $media->setCustomProperty('width', $size[0])
            ->setCustomProperty('height', $size[1])
            ->save();
    }
}
