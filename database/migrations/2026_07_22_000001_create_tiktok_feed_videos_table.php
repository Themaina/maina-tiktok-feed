<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('tiktok_feed_videos')) {
            Schema::create('tiktok_feed_videos', function (Blueprint $table): void {
                $table->id();
                $table->string('title', 250)->nullable();
                $table->string('video_url', 400);
                $table->string('video_id', 60)->nullable();
                $table->string('product_url', 400)->nullable();
                $table->integer('order')->default(0);
                $table->string('status', 60)->default('published');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('tiktok_feed_videos');
    }
};
