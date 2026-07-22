<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Regenerate the static sitemap.xml / llms.txt overnight. Content changes rarely
// enough that daily is plenty; run `php artisan seo:generate` after a big edit.
Schedule::command('seo:generate')->dailyAt('03:30')->withoutOverlapping();
