<?php

namespace KodiCMS\CMS\Providers;

use KodiCMS\CMS\CMS;
use Collective\Html\FormFacade;
use Collective\Html\HtmlFacade;
use KodiCMS\CMS\Navigation\Page;
use KodiCMS\Support\Facades\Wysiwyg;
use KodiCMS\Support\Helpers\Profiler;
use Illuminate\Foundation\AliasLoader;
use KodiCMS\CMS\Navigation\Navigation;
use KodiCMS\Assets\AssetsServiceProvider;
use KodiCMS\Support\Loader\ModulesLoader;
use KodiCMS\Support\Facades\DatabaseConfig;
use KodiCMS\ModulesLoader\ModulesFileSystem;
use KodiCMS\Support\Html\HtmlServiceProvider;
use KodiCMS\ModulesLoader\ModulesLoaderFacade;
use KodiCMS\ModulesLoader\ModulesFileSystemFacade;
use KodiCMS\ModulesLoader\Providers\AppServiceProvider;
use KodiCMS\ModulesLoader\Providers\RouteServiceProvider;
use KodiCMS\ModulesLoader\Providers\ConfigServiceProvider;
use KodiCMS\ModulesLoader\Providers\ModuleServiceProvider as BaseModuleServiceProvider;

class ModuleLoaderServiceProvider extends BaseModuleServiceProvider
{
    /**
     * Providers to register.
     * @var array
     */
    protected $providers = [
        10 => RouteServiceProvider::class,
        20 => EventServiceProvider::class,
        30 => AppServiceProvider::class,
        40 => ConfigServiceProvider::class,
        60 => AssetsServiceProvider::class,
        70 => HtmlServiceProvider::class,
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
                    'path'      => base_path('vendor'.DIRECTORY_SEPARATOR.'kodicms'.DIRECTORY_SEPARATOR.'api'.DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR)
                ],
                'CMS' => [
                    'namespace' => '\\KodiCMS\\CMS\\',
                    'path'      => base_path('vendor'.DIRECTORY_SEPARATOR.'kodicms'.DIRECTORY_SEPARATOR.'core'.DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR)
                ]
            ];

            $modules = array_merge($modules, config('app.modules', []));

            if (file_exists($path = base_path('bootstrap'.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.'modules.php'))) {
                $modules = array_merge($modules, include $path);
            }

            return new ModulesLoader($modules);
        });

        $this->app->bind(\KodiComponents\Navigation\Contracts\PageInterface::class, Page::class);

        $this->app->singleton('navigation', function () {
            return new Navigation();
        });

        $this->app->singleton('modules.filesystem', function ($app) {
            return new ModulesFileSystem($app['modules.loader'], $app['files']);
        });

        $this->registerAliases();
        $this->registerProviders();
        $this->registerConsoleCommands();
    }

    /**
     * Register aliases.
     */
    protected function registerAliases()
    {
        AliasLoader::getInstance([
            'ModulesLoader'     => ModulesLoaderFacade::class,
            'ModulesFileSystem' => ModulesFileSystemFacade::class,
            'CMS'               => CMS::class,
            'DatabaseConfig'    => DatabaseConfig::class,
            'Profiler'          => Profiler::class,
            'WYSIWYG'           => Wysiwyg::class,
            'Form'              => FormFacade::class,
            'HTML'              => HtmlFacade::class,
            'Navigation'        => \KodiCMS\Support\Facades\Navigation::class
        ]);
    }
}
