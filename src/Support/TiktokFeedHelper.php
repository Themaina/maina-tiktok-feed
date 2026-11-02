<?php

namespace MainaSuperintelligence\TiktokFeed\Support;

use Botble\Setting\Facades\Setting;
use MainaSuperintelligence\TiktokFeed\Models\TiktokVideo;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Route;
use Throwable;

class TiktokFeedHelper
{
    public function getSettingKeyPrefix(): string
    {
        return 'tiktok_feed';
    }

    public function getSetting(string $key, mixed $default = null): mixed
    {
        return setting("{$this->getSettingKeyPrefix()}_$key", $default);
    }

    public function saveSettings(array $settings): void
    {
        foreach ($settings as $settingKey => $settingValue) {
            Setting::set("{$this->getSettingKeyPrefix()}_$settingKey", $settingValue);
        }

        Setting::save();
    }

    public function settingKeys(): array
    {
        return [
            'enabled',
            'position',
            'draggable',
            'tiktok_username',
            'shop_button_text',
            'shop_button_url',
            'show_on_mobile',
            'limit_videos',
            'auto_open',
            'auto_open_delay',
            'display_pages',
            'widget_size',
            'follow_button_text',
            'click_to_load',
            'mobile_bottom_offset',
        ];
    }

    public function positions(): array
    {
        return [
            'bottom-left' => trans('plugins/tiktok-feed::tiktok-feed.positions.bottom_left'),
            'bottom-right' => trans('plugins/tiktok-feed::tiktok-feed.positions.bottom_right'),
            'top-left' => trans('plugins/tiktok-feed::tiktok-feed.positions.top_left'),
            'top-right' => trans('plugins/tiktok-feed::tiktok-feed.positions.top_right'),
        ];
    }

    public function sizes(): array
    {
        return [
            'compact' => trans('plugins/tiktok-feed::tiktok-feed.sizes.compact'),
            'standard' => trans('plugins/tiktok-feed::tiktok-feed.sizes.standard'),
            'spacious' => trans('plugins/tiktok-feed::tiktok-feed.sizes.spacious'),
        ];
    }

    public function displayPages(): array
    {
        $pages = [
            'all' => trans('plugins/tiktok-feed::tiktok-feed.display_pages.all'),
            'public.index' => trans('plugins/tiktok-feed::tiktok-feed.display_pages.homepage'),
        ];

        // Shop pages only exist when the Ecommerce plugin is active.
        if (is_plugin_active('ecommerce')) {
            $pages += [
                'public.products' => trans('plugins/tiktok-feed::tiktok-feed.display_pages.products'),
                'product_detail' => trans('plugins/tiktok-feed::tiktok-feed.display_pages.product_detail'),
                'public.cart' => trans('plugins/tiktok-feed::tiktok-feed.display_pages.cart'),
                'public.checkout.information' => trans('plugins/tiktok-feed::tiktok-feed.display_pages.checkout'),
            ];
        }

        return $pages;
    }

    public function getDisplayPages(): array
    {
        $pages = $this->getSetting('display_pages', ['all']);

        if (is_string($pages)) {
            $pages = json_decode($pages, true) ?: [];
        }

        return is_array($pages) && $pages ? $pages : ['all'];
    }

    public function defaultShopButtonUrl(): string
    {
        try {
            if (Route::has('public.products')) {
                return route('public.products');
            }
        } catch (Throwable) {
            // fallback to homepage below
        }

        return url('/');
    }

    public function profileUrl(): ?string
    {
        return TiktokUrl::profileUrl($this->getSetting('tiktok_username'));
    }

    /** @deprecated Use profileUrl(). */
    public function tiktokProfileUrl(): ?string
    {
        return $this->profileUrl();
    }

    public function getVideos(): Collection
    {
        $limit = (int) $this->getSetting('limit_videos', 6);

        return TiktokVideo::query()
            ->wherePublished()
            ->orderBy('order')
            ->orderByDesc('id')
            ->limit($limit > 0 ? $limit : 6)
            ->get()
            ->filter(fn (TiktokVideo $video) => (bool) $video->video_id)
            ->values();
    }
}
