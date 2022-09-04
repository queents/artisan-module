<?php

namespace Modules\Artisan\Providers;

use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\ServiceProvider;
use Modules\Base\Services\Components\Base\Lang;
use Modules\Base\Services\Components\Base\Menu;
use Modules\Base\Services\Core\VILT;

class ArtisanServiceProvider extends ServiceProvider
{
    /**
     * @var string $moduleName
     */
    protected $moduleName = 'Artisan';

    /**
     * @var string $moduleNameLower
     */
    protected $moduleNameLower = 'artisan';

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerConfig();

        VILT::registerTranslation(Lang::make('artisan.sidebar')->label(__('Artisan Terminal')));
        VILT::loadPages($this->moduleName);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            module_path($this->moduleName, 'Config/config.php') => config_path($this->moduleNameLower . '.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path($this->moduleName, 'Config/config.php'),
            $this->moduleNameLower
        );
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
