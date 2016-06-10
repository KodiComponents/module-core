<?php

namespace KodiCMS\CMS\Providers;

use Illuminate\Foundation\AliasLoader;
use KodiCMS\ModulesLoader\Providers\ModuleServiceProvider as BaseModuleServiceProvider;

class ModuleLoaderServiceProvider extends BaseModuleServiceProvider
{

    /**
     * Providers to register.
     * @var array
     */
    protected $providers = [
        10 => \KodiCMS\ModulesLoader\Providers\RouteServiceProvider::class,
        20 => EventServiceProvider::class,
        30 => \KodiCMS\ModulesLoader\Providers\AppServiceProvider::class,
        40 => \KodiCMS\ModulesLoader\Providers\ConfigServiceProvider::class,
        60 => \KodiCMS\Assets\AssetsServiceProvider::class,
        70 => \KodiCMS\Support\Html\HtmlServiceProvider::class,
        100 => \Intervention\Image\ImageServiceProviderLaravel5::class,
    ];

    /**
     * Register any application services.
     *
     * This service provider is a great spot to register your various container
     * bindings with the application. As you can see, we are registering our
     * "Registrar" implementation here. You can add your own bindings too!
     *
     * @return void
     */
    public function register()
    {
        $this->registerCoreProviders();
        $this->registerBackendNavigation();

        $this->app->singleton('cms', function () {
            return new \KodiCMS\CMS\CMS();
        });

        $this->registerAliases();
        $this->registerProviders();

        if ($this->app->runningInConsole()) {
            $this->commands($this->commands);
        }
    }

    /**
     * Register aliases.
     */
    protected function registerAliases()
    {
        AliasLoader::getInstance([
            'ModulesLoader' => \KodiCMS\ModulesLoader\ModulesLoaderFacade::class,
            'ModulesFileSystem' => \KodiCMS\ModulesLoader\ModulesFileSystemFacade::class,
            'CMS' => \KodiCMS\Support\Facades\CMS::class,
            'DatabaseConfig' => \KodiCMS\Support\Facades\DatabaseConfig::class,
            'Profiler' => \KodiCMS\Support\Helpers\Profiler::class,
            'WYSIWYG' => \KodiCMS\Support\Facades\Wysiwyg::class,
            'Form' => \Collective\Html\FormFacade::class,
            'HTML' => \Collective\Html\HtmlFacade::class,
            'Navigation' => \KodiCMS\Support\Facades\Navigation::class,
        ]);
    }

    private function registerCoreProviders()
    {
        if (class_exists($provider = '\KodiCMS\Users\Providers\AuthServiceProvider')) {
            $this->providers[50] = $provider;
        }

        if (class_exists($provider = '\KodiCMS\Plugins\Providers\PluginServiceProvider')) {
            $this->providers[0] = $provider;
        }

        ksort($this->providers);
        $this->app->singleton('modules.loader', function () {
            $modules = [
                'API' => [
                    'namespace' => '\\KodiCMS\\API\\',
                    'path' => base_path('vendor'.DIRECTORY_SEPARATOR.'kodicms'.DIRECTORY_SEPARATOR.'api'.DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR),
                ],
                'CMS' => [
                    'namespace' => '\\KodiCMS\\CMS\\',
                    'path' => base_path('vendor'.DIRECTORY_SEPARATOR.'kodicms'.DIRECTORY_SEPARATOR.'core'.DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR),
                ],
            ];

            $modules = array_merge($modules, config('app.modules', []));

            if (file_exists($path = base_path('bootstrap'.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.'modules.php'))) {
                $modules = array_merge($modules, include $path);
            }

            return new \KodiCMS\Support\Loader\ModulesLoader($modules);
        });

        $this->app->singleton('modules.filesystem', function ($app) {
            return new \KodiCMS\ModulesLoader\ModulesFileSystem($app['modules.loader'], $app['files']);
        });
    }

    private function registerBackendNavigation()
    {
        $this->app->bind(\KodiComponents\Navigation\Contracts\PageInterface::class, \KodiCMS\CMS\Navigation\Page::class);

        $this->app->singleton('navigation', function () {
            return new \KodiCMS\CMS\Navigation\Navigation();
        });
    }
}
