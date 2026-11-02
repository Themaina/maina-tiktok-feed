<?php

namespace MainaSuperintelligence\TiktokFeed\Providers;

use Botble\Base\Facades\DashboardMenu;
use Botble\Base\Facades\PanelSectionManager;
use Botble\Base\PanelSections\PanelSectionItem;
use Botble\Base\Supports\ServiceProvider;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\Setting\PanelSections\SettingOthersPanelSection;
use Illuminate\Foundation\Application;

class TiktokFeedServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function boot(): void
    {
        $this
            ->setNamespace('plugins/tiktok-feed')
            ->loadAndPublishConfigurations(['permissions'])
            ->loadAndPublishTranslations()
            ->loadAndPublishViews()
            ->loadMigrations()
            ->publishAssets()
            ->loadRoutes();

        $this->app->booted(fn (Application $app) => $app->register(HookServiceProvider::class));

        DashboardMenu::beforeRetrieving(function (): void {
            DashboardMenu::make()
                ->registerItem([
                    'id' => 'cms-plugins-tiktok-feed',
                    'priority' => 6,
                    'parent_id' => null,
                    'name' => 'plugins/tiktok-feed::tiktok-feed.name',
                    'icon' => 'ti ti-brand-tiktok',
                    'url' => route('tiktok-feed.index'),
                    'permissions' => ['tiktok-feed.index'],
                ]);
        });

        PanelSectionManager::beforeRendering(function (): void {
            PanelSectionManager::default()
                ->registerItem(
                    SettingOthersPanelSection::class,
                    fn () => PanelSectionItem::make('tiktok-feed-settings')
                        ->setTitle(trans('plugins/tiktok-feed::tiktok-feed.settings.name'))
                        ->withIcon('ti ti-brand-tiktok')
                        ->withDescription(trans('plugins/tiktok-feed::tiktok-feed.settings.description'))
                        ->withPriority(200)
                        ->withRoute('tiktok-feed.settings')
                );
        });
    }
}
