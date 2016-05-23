<?php

namespace KodiCMS\CMS\Providers;

use Blade;
use Cache;
use KodiCMS\CMS\CMS;
use KodiCMS\Support\Helpers\UI;
use KodiCMS\Assets\Facades\Meta;
use KodiCMS\Support\Helpers\Date;
use KodiCMS\Assets\Facades\Assets;
use KodiCMS\Support\ServiceProvider;
use KodiCMS\Assets\Facades\PackageManager;
use KodiCMS\Support\Cache\SqLiteTaggedStore;
use KodiCMS\Support\Cache\DatabaseTaggedStore;
use KodiCMS\CMS\Console\Commands\WysiwygListCommand;
use KodiCMS\CMS\Console\Commands\ModuleInstallCommand;
use KodiCMS\CMS\Console\Commands\ModulePublishCommand;
use KodiCMS\CMS\Console\Commands\ControllerMakeCommand;
use KodiCMS\CMS\Console\Commands\ModuleLocaleDiffCommand;
use KodiCMS\CMS\Console\Commands\ModuleLocalePublishCommand;
use KodiCMS\CMS\Console\Commands\GenerateScriptTranslatesCommand;
use KodiCMS\Users\Model\Permission;

class ModuleServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->registerAliases([
            'UI'             => UI::class,
            'Date'           => Date::class,
            'Assets'         => Assets::class,
            'PackageManager' => PackageManager::class,
            'Meta'           => Meta::class,
        ]);

        $this->registerConsoleCommand([
            GenerateScriptTranslatesCommand::class,
            ModuleLocalePublishCommand::class,
            ModuleLocaleDiffCommand::class,
            ControllerMakeCommand::class,
            ModulePublishCommand::class,
            WysiwygListCommand::class,
            ModuleInstallCommand::class
        ]);
        
        Permission::register('core', 'system', [
            'view_phpinfo'
        ]);

        $this->app->singleton('cms', CMS::class);
    }

    public function boot()
    {
        Blade::directive('event', function ($expression) {
            return "<?php event{$expression}; ?>";
        });

        $this->publishes([
             __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'cms' => public_path('cms')
         ], 'kodicms');

        $this->registerCacheDrivers();
    }

    protected function registerCacheDrivers()
    {
        Cache::extend('sqlite', function ($app, $config) {
            $connectionName = array_get($config, 'connection');
            $connectionConfig = config('database.connections.'.$connectionName);

            if (! file_exists($connectionConfig['database'])) {
                touch($connectionConfig['database']);
            }

            $connection = $this->app['db']->connection($connectionName);

            return Cache::repository(new SqLiteTaggedStore($connection, $config['schema']));
        });

        Cache::extend('database', function ($app, $config) {
            $connection = $this->app['db']->connection(array_get($config, 'connection'));

            return Cache::repository(new DatabaseTaggedStore($connection, $config['table']));
        });
    }
}
