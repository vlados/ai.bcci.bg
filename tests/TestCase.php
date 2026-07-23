<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Storage;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // SiteSeeder attaches article covers through the media library, so
        // without this every test that seeds would write into the real
        // storage/app/public alongside the development site's own files.
        Storage::fake('public');
    }
}
