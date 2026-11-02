<?php

namespace Maina888\TiktokFeed;

use Botble\PluginManagement\Abstracts\PluginOperationAbstract;
use Botble\Setting\Facades\Setting;
use Illuminate\Support\Facades\Schema;

class Plugin extends PluginOperationAbstract
{
    public static function remove(): void
    {
        Schema::dropIfExists('tiktok_feed_videos');

        Setting::newQuery()
            ->where('key', 'LIKE', 'tiktok_feed_%')
            ->delete();
    }
}
