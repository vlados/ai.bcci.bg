<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Process;

/**
 * Renders the news cover images in `public/assets/news/<slug>.png`.
 *
 * A development-time command, and a sibling of `seo:og`: it drives headless
 * Chrome so the covers use the site's real fonts and brand tokens, then hands
 * the result to ImageMagick to land on exactly 1200x800. Neither tool is
 * needed in production — the PNGs are committed.
 *
 * The articles are Eurostat data journalism, so the covers are the figures
 * themselves rather than stock photography. They carry no prose: a source
 * plate, the two series in the site's own colours (Bulgaria red, EU-27 blue)
 * and the numbers. That keeps one image valid for both locales, which matters
 * because `news_articles.image_url` is a single column, not a translated one.
 *
 * Edit resources/news/cover.html to change the design.
 */
class GenerateNewsCovers extends Command
{
    protected $signature = 'news:covers {--chrome= : Path to a Chrome/Chromium binary}';

    protected $description = 'Render the news cover images from resources/news/cover.html';

    /**
     * One entry per article, keyed by slug.
     *
     * Every figure below is quoted verbatim from the article it covers, which
     * in turn cites the Eurostat dataset named in the plate. Decimals use a
     * point, as Eurostat publishes them — the covers are language-neutral, so
     * they cannot follow the Bulgarian comma in one locale and the point in
     * the other.
     *
     * @var array<string, array{plate: string, rows: array<int, array{0: string, 1: string}>}>
     */
    protected array $covers = [
        // 8.55% of Bulgarian enterprises used AI in 2025, against 19.95% EU-wide.
        'ai-adoption-gap-bulgaria-eu-eurostat-2025' => [
            'plate' => 'EUROSTAT · isoc_eb_ai · 2025',
            'rows' => [['8.55%', '19.95%']],
        ],

        // The article's thesis in two lines: among enterprises that considered
        // AI, the top reason for not adopting it — no relevant expertise — is
        // near-identical on both sides, while "not useful for the enterprise"
        // is the smallest of the four. The barrier is capacity, not scepticism.
        'why-companies-that-considered-ai-decided-against-it' => [
            'plate' => 'EUROSTAT · isoc_eb_ai · 2025',
            'rows' => [
                ['72.69%', '70.31%'],
                ['16.26%', '17.79%'],
            ],
        ],

        // The broad comparison: basic digital skills, enterprise AI, use of
        // online public services, and use of electronic identification. The
        // same gap, four times over.
        'ai-and-digitalisation-in-bulgaria-2026' => [
            'plate' => 'EUROSTAT · 2025',
            'rows' => [
                ['38%', '60%'],
                ['8.55%', '19.95%'],
                ['36%', '72%'],
                ['12%', '52%'],
            ],
        ],
    ];

    public function handle(): int
    {
        $chrome = $this->option('chrome') ?: $this->findChrome();

        if (! $chrome) {
            $this->error('No Chrome/Chromium found. Pass one with --chrome=/path/to/chrome.');

            return self::FAILURE;
        }

        $template = resource_path('news/cover.html');
        if (! is_file($template)) {
            $this->error("Missing template: {$template}");

            return self::FAILURE;
        }

        $html = file_get_contents($template);
        $dir = public_path('assets/news');
        @mkdir($dir, 0755, true);

        $tmp = sys_get_temp_dir().'/covers-'.getmypid();
        @mkdir($tmp);

        foreach ($this->covers as $slug => $cover) {
            $page = "{$tmp}/{$slug}.html";
            $raw = "{$tmp}/{$slug}.png";
            $out = "{$dir}/{$slug}.png";

            file_put_contents($page, str_replace(
                ['{{COUNT}}', '{{PLATE}}', '{{ROWS}}'],
                [count($cover['rows']), e($cover['plate']), $this->rows($cover['rows'])],
                $html,
            ));

            // Rendered at 2x then downsampled, so the figures stay crisp at the
            // 1200x800 the article hero and the card grid actually use.
            Process::timeout(120)->run([
                $chrome, '--headless', '--disable-gpu', '--hide-scrollbars', '--no-sandbox',
                '--force-device-scale-factor=2', '--window-size=1200,800',
                '--virtual-time-budget=8000', "--screenshot={$raw}", "file://{$page}",
            ]);

            if (! is_file($raw)) {
                $this->error("Chrome produced no image for {$slug}.");

                return self::FAILURE;
            }

            $magick = Process::run(['magick', $raw, '-resize', '1200x800', '-strip',
                '-define', 'png:compression-level=9', $out]);

            if (! $magick->successful()) {
                // No ImageMagick: keep the 2x render, which is still valid.
                copy($raw, $out);
                $this->warn("ImageMagick unavailable — kept the 2x render for {$slug}.");
            }

            $this->info(sprintf('%s  (%s KB)', $out, number_format(filesize($out) / 1024)));
        }

        return self::SUCCESS;
    }

    /** @param array<int, array{0: string, 1: string}> $rows */
    protected function rows(array $rows): string
    {
        return collect($rows)->map(fn (array $r) => sprintf(
            '<div class="row grid"><span class="bg col-bg">%s</span><span class="eu col-eu">%s</span></div>',
            e($r[0]),
            e($r[1]),
        ))->implode("\n      ");
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
