<?php

namespace Moe\Settings;

use Illuminate\Support\ServiceProvider;
use Moe\Settings\Defaults\DefaultSettingGroups;
use Moe\Settings\Services\SettingService;

class MoeSettingsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/moe-settings.php', 'moe-settings');

        $this->app->singleton('moe.settings', function ($app) {
            return new SettingService($app['cache'], $app['config']);
        });
    }

    public function boot(): void
    {
        // Daftarkan grup setting generic (sama di semua app).
        DefaultSettingGroups::register();

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'moe-settings');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/moe-settings.php' => config_path('moe-settings.php'),
            ], 'moe-settings-config');

            $this->publishes([
                __DIR__ . '/../database/migrations/create_settings_table.php' => database_path('migrations/' . date('Y_m_d_His') . '_create_settings_table.php'),
            ], 'moe-settings-migration');

            $this->publishes([
                __DIR__ . '/../resources/views' => resource_path('views/vendor/moe-settings'),
            ], 'moe-settings-views');
        }
    }
}
