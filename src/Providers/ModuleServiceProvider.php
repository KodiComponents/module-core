<?php

namespace KodiCMS\CMS\Providers;

use Blade;
use Cache;
use KodiCMS\Support\ServiceProvider;
use KodiCMS\Users\Model\Permission;
use Navigation;

class ModuleServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->registerAliases([
            'UI' => \KodiCMS\Support\Helpers\UI::class,
            'Date' => \KodiCMS\Support\Helpers\Date::class,
            'Assets' => \KodiCMS\Assets\Facades\Assets::class,
            'PackageManager' => \KodiCMS\Assets\Facades\PackageManager::class,
            'Meta' => \KodiCMS\Assets\Facades\Meta::class,
        ]);

        $this->registerConsoleCommand([
            \KodiCMS\CMS\Console\Commands\GenerateScriptTranslatesCommand::class,
            \KodiCMS\CMS\Console\Commands\ModuleLocalePublishCommand::class,
            \KodiCMS\CMS\Console\Commands\ModuleLocaleDiffCommand::class,
            \KodiCMS\CMS\Console\Commands\ControllerMakeCommand::class,
            \KodiCMS\CMS\Console\Commands\ModulePublishCommand::class,
            \KodiCMS\CMS\Console\Commands\WysiwygListCommand::class,
            \KodiCMS\CMS\Console\Commands\ModuleInstallCommand::class,
        ]);
    }

    public function boot()
    {
        Blade::directive('event', function ($expression) {
            return "<?php event{$expression}; ?>";
        });

        $this->publishes([
            __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'cms' => public_path('cms'),
        ], 'kodicms');

        $this->registerCacheDrivers();
        $this->registerPermissions();
    }

    protected function registerCacheDrivers()
    {
        Cache::extend('sqlite', function ($app, $config) {
            $connectionName   = array_get($config, 'connection');
            $connectionConfig = config('database.connections.'.$connectionName);

            if (! file_exists($connectionConfig['database'])) {
                touch($connectionConfig['database']);
            }

            $connection = $this->app['db']->connection($connectionName);

            return Cache::repository(new \KodiCMS\Support\Cache\SqLiteTaggedStore($connection, $config['schema']));
        });

        Cache::extend('database', function ($app, $config) {
            $connection = $this->app['db']->connection(array_get($config, 'connection'));

            return Cache::repository(new \KodiCMS\Support\Cache\DatabaseTaggedStore($connection, $config['table']));
        });
    }

    public function contextBackend()
    {
        Navigation::setFromArray([
            [
                'title' => 'cms::core.title.design',
                'id' => 'design',
                'icon' => 'desktop',
                'priority' => 7000,
            ],
            [
                'title' => 'cms::core.title.system',
                'id' => 'system',
                'icon' => 'cog',
                'priority' => 8000,
                'pages' => [
                    [
                        'title' => 'cms::core.title.about',
                        'id' => 'information',
                        'icon' => 'info-circle',
                        'permissions' => 'system::view_about',
                        'url' => route('backend.about'),
                        'priority' => 90,
                    ],
                    [
                        'title' => 'cms::core.title.settings',
                        'id' => 'settings',
                        'url' => route('backend.settings'),
                        'permissions' => 'system::view_settings',
                        'priority' => 100,
                        'icon' => 'cog',
                    ],
                ],
            ],
        ]);
    }

    private function registerPermissions()
    {
        Permission::register('cms', 'system', [
            'view_phpinfo',
            'view_about',
            'view_settings',
        ]);
    }
}
