<?php

namespace App\Console\Commands;

use App\Support\LlmsTxtBuilder;
use App\Support\SitemapBuilder;
use Illuminate\Console\Command;

/**
 * Writes the machine-readable files crawlers and answer-engines fetch, so the
 * web server can serve them straight off disk — no Laravel boot, no queries.
 *
 * Run nightly by the scheduler, and by hand after a large content edit.
 */
class GenerateSeoFiles extends Command
{
    protected $signature = 'seo:generate';

    protected $description = 'Write public/sitemap.xml and public/llms.txt as static files';

    public function handle(SitemapBuilder $sitemap, LlmsTxtBuilder $llms): int
    {
        $sitemapPath = public_path('sitemap.xml');
        $sitemap->build()->writeToFile($sitemapPath);
        $this->info('Wrote '.$sitemapPath);

        $llmsPath = public_path('llms.txt');
        file_put_contents($llmsPath, $llms->build());
        $this->info('Wrote '.$llmsPath);

        return self::SUCCESS;
    }
}
