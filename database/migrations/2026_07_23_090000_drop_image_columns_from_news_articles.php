<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Cover images moved to spatie/laravel-medialibrary, which keeps them in the
 * `media` table against a `cover` collection. The two columns that used to
 * hold them — an upload path and an external URL — have no reader left.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('news_articles', function (Blueprint $table) {
            $table->dropColumn(['image', 'image_url']);
        });
    }

    public function down(): void
    {
        Schema::table('news_articles', function (Blueprint $table) {
            $table->string('image')->nullable()->after('body');
            $table->string('image_url')->nullable()->after('image');
        });
    }
};
