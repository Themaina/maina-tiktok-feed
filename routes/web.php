<?php

use Botble\Base\Facades\AdminHelper;
use Maina888\TiktokFeed\Http\Controllers\Settings\TiktokFeedSettingController;
use Maina888\TiktokFeed\Http\Controllers\TiktokVideoController;
use Illuminate\Support\Facades\Route;

AdminHelper::registerRoutes(function (): void {
    Route::group(['prefix' => 'tiktok-feed', 'as' => 'tiktok-feed.'], function (): void {
        Route::resource('', TiktokVideoController::class)->parameters(['' => 'video']);
    });

    Route::group(['prefix' => 'settings/tiktok-feed', 'as' => 'tiktok-feed.settings', 'permission' => 'tiktok-feed.settings'], function (): void {
        Route::get('/', [TiktokFeedSettingController::class, 'edit']);
        Route::put('/', [TiktokFeedSettingController::class, 'update'])->name('.edit');
    });
});
