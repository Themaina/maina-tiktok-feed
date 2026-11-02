<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('tiktok_feed_videos') && ! Schema::hasColumn('tiktok_feed_videos', 'cover_image')) {
            Schema::table('tiktok_feed_videos', function (Blueprint $table): void {
                $table->string('cover_image', 255)->nullable()->after('product_url');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('tiktok_feed_videos', 'cover_image')) {
            Schema::table('tiktok_feed_videos', function (Blueprint $table): void {
                $table->dropColumn('cover_image');
            });
        }
    }
};
