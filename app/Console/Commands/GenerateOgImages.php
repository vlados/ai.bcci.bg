<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Process;

/**
 * Renders the social sharing cards in `public/assets/og-<locale>.png`.
 *
 * A development-time command: it drives headless Chrome so the cards use the
 * site's real fonts and brand tokens rather than an approximation, then hands
 * the result to ImageMagick to land on exactly 1200x630. Neither tool is
 * needed in production — the PNGs are committed.
 *
 * Edit resources/og/card.html to change the design.
 */
class GenerateOgImages extends Command
{
    protected $signature = 'seo:og {--chrome= : Path to a Chrome/Chromium binary}';

    protected $description = 'Render the Open Graph social cards from resources/og/card.html';

    /** Taglines per locale. The logo already carries the organisation name. */
    protected array $taglines = [
        'bg' => 'Консултативен орган<br>към БТПП',
        'en' => 'An advisory body to the<br>Bulgarian Chamber of<br>Commerce and Industry',
    ];

    public function handle(): int
    {
        $chrome = $this->option('chrome') ?: $this->findChrome();

        if (! $chrome) {
            $this->error('No Chrome/Chromium found. Pass one with --chrome=/path/to/chrome.');

            return self::FAILURE;
        }

        $template = resource_path('og/card.html');
        if (! is_file($template)) {
            $this->error("Missing template: {$template}");

            return self::FAILURE;
        }

        // Inline the logo: headless Chrome will not reliably load a sibling
        // file:// image, and a data URI removes the question entirely.
        $logo = 'data:image/png;base64,'.base64_encode(file_get_contents(public_path('assets/logo.png')));
        $html = file_get_contents($template);
        $tmp = sys_get_temp_dir().'/og-'.getmypid();
        @mkdir($tmp);

        foreach ($this->taglines as $locale => $tagline) {
            $page = "{$tmp}/card-{$locale}.html";
            $raw = "{$tmp}/card-{$locale}.png";
            $out = public_path("assets/og-{$locale}.png");

            file_put_contents($page, str_replace(
                ['{{LOGO}}', '{{LANG}}', '{{TAGLINE}}'],
                [$logo, $locale, $tagline],
                $html,
            ));

            // Rendered at 2x then downsampled: text and the logo stay crisp,
            // and the file still lands on the 1200x630 every platform expects.
            Process::timeout(120)->run([
                $chrome, '--headless', '--disable-gpu', '--hide-scrollbars', '--no-sandbox',
                '--force-device-scale-factor=2', '--window-size=1200,630',
                '--virtual-time-budget=8000', "--screenshot={$raw}", "file://{$page}",
            ]);

            if (! is_file($raw)) {
                $this->error("Chrome produced no image for {$locale}.");

                return self::FAILURE;
            }

            $magick = Process::run(['magick', $raw, '-resize', '1200x630', '-strip',
                '-define', 'png:compression-level=9', $out]);

            if (! $magick->successful()) {
                // No ImageMagick: keep the 2x render, which is still valid.
                copy($raw, $out);
                $this->warn('ImageMagick unavailable — kept the 2x render for '.$locale.'.');
            }

            $this->info(sprintf('%s  (%s KB)', $out, number_format(filesize($out) / 1024)));
        }

        return self::SUCCESS;
    }

    protected function findChrome(): ?string
    {
        $candidates = [
            '/Applications/Google Chrome.app/Contents/MacOS/Google Chrome',
            '/Applications/Chromium.app/Contents/MacOS/Chromium',
            '/usr/bin/google-chrome',
            '/usr/bin/chromium',
            '/usr/bin/chromium-browser',
        ];

        foreach ($candidates as $path) {
            if (is_executable($path)) {
                return $path;
            }
        }

        return null;
    }
}
