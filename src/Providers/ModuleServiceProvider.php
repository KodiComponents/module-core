<?php

namespace KodiCMS\CMS\Providers;

use Blade;
use Cache;
use KodiCMS\Assets\Facades\Assets;
use KodiCMS\Assets\Facades\Meta;
use KodiCMS\Assets\Facades\PackageManager;
use KodiCMS\CMS\CMS;
use KodiCMS\CMS\Console\Commands\ControllerMakeCommand;
use KodiCMS\CMS\Console\Commands\GenerateScriptTranslatesCommand;
use KodiCMS\CMS\Console\Commands\ModuleInstallCommand;
use KodiCMS\CMS\Console\Commands\ModuleLocaleDiffCommand;
use KodiCMS\CMS\Console\Commands\ModuleLocalePublishCommand;
use KodiCMS\CMS\Console\Commands\ModulePublishCommand;
use KodiCMS\CMS\Console\Commands\WysiwygListCommand;
use KodiCMS\Support\Cache\DatabaseTaggedStore;
use KodiCMS\Support\Cache\SqLiteTaggedStore;
use KodiCMS\Support\Helpers\Date;
use KodiCMS\Support\Helpers\UI;
use KodiCMS\Support\ServiceProvider;
use KodiCMS\Users\Model\Permission;
use Navigation;

class ModuleServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->registerMiddlewares();

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
    }

    public function boot()
    {
        // TODO fix bug
        spl_autoload_call(\KodiCMS\CMS\Exceptions\Handler::class);

        $this->app->singleton(
            \Illuminate\Contracts\Debug\ExceptionHandler::class,
            \KodiCMS\CMS\Exceptions\Handler::class
        );

        Blade::directive('event', function ($expression) {
            return "<?php event{$expression}; ?>";
        });

        $this->publishes([
             __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'cms' => public_path('cms')
         ], 'kodicms');

        $this->registerCacheDrivers();
        $this->registerPermissions();
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
                        'permissions' => 'system.view_about',
                        'url' => route('backend.about'),
                        'priority' => 90,
                    ],
                    [
                        'title' => 'cms::core.title.settings',
                        'id' => 'settings',
                        'url' => route('backend.settings'),
                        'permissions' => 'system.view_settings',
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
            'view_settings'
        ]);
    }

    private function registerMiddlewares()
    {
        /** @var \Illuminate\Routing\Router $router */
        $router = $this->app['router'];

        $router->middleware('context', \KodiCMS\CMS\Http\Middleware\Context::class);

        $router->middlewareGroup('backend', [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \KodiCMS\CMS\Http\Middleware\VerifyCsrfToken::class,
            \KodiCMS\CMS\Http\Middleware\PostJson::class,
            'context:'.CMS::CONTEXT_BACKEND,
            'backend.auth',
        ]);
    }
}
