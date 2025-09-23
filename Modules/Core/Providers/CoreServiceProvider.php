<?php

namespace Modules\Core\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Modules\Core\Console\InstallApplication;
use Modules\Core\Console\InstallApplicationDirect;
use Modules\Core\Console\ResetApplication;
use Modules\Core\Console\ResetDatabase;
use Modules\Core\Http\Middleware\LicenseVerification;
use Modules\Setting\Entities\Setting;

class CoreServiceProvider extends ServiceProvider
{
    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        $this->app->register(RouteServiceProvider::class);
        if (config('app.installed')) {
            $companyName = Setting::where('setting_key', 'core.company_name')->first();
            $companyLogo = Setting::where('setting_key', 'core.company_logo')->first();
            $systemVersion = Setting::where('setting_key', 'core.system_version')->first();
            
            View::share('company_name', $companyName ? $companyName->setting_value : 'Ultimate Loan Manager');
            View::share('company_logo', $companyLogo ? $companyLogo->setting_value : '');
            View::share('system_version', $systemVersion ? $systemVersion->setting_value : '1.0');
        }
        Paginator::useBootstrap();
        $router->pushMiddlewareToGroup('web',LicenseVerification::class);

    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->commands([
            InstallApplication::class,
            ResetDatabase::class,
            InstallApplicationDirect::class,
            ResetApplication::class,
        ]);

    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            __DIR__ . '/../Config/config.php' => config_path('core.php'),
        ], 'config');
        $this->mergeConfigFrom(
            __DIR__ . '/../Config/config.php', 'core'
        );
        $this->mergeConfigFrom(
            __DIR__ . '/../Config/permissions.php', 'core.permissions'
        );
        $this->mergeConfigFrom(
            __DIR__ . '/../Config/menus.php', 'core.menus'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/core');

        $sourcePath = __DIR__ . '/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ], 'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/core';
        }, Config::get('view.paths')), [$sourcePath]), 'core');
        $theme = 'AdminLTE';
        if (config('app.installed')) {
            $default_theme = Setting::where('setting_key', 'core.active_theme')->first();
            if ($default_theme && $default_theme->setting_value) {
                $theme = $default_theme->setting_value;
            }
        }
        Config::set('active_theme', $theme);
        Config::set('google2fa.view', "user::themes." . strtolower($theme) . ".google2fa.index");
        $themePath = base_path("themes/" . strtolower($theme) . "/views");
        $this->loadViewsFrom($themePath, 'core');
        //$this->app['view']->getFinder()->prependLocation($themePath);
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/core');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'core');
        } else {
            $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'core');
        }
    }


    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }

}
