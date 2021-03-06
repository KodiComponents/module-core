<?php

namespace KodiCMS\CMS\Providers;

use Config;
use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as BaseEventServiceProvider;
use PDOException;
use Profiler;
use WYSIWYG;

class EventServiceProvider extends BaseEventServiceProvider
{

    /**
     * The event handler mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'KodiCMS\CMS\Events\BackendSettingsValidate' => [
            'KodiCMS\CMS\Listeners\BackendSettingsValidateListener',
        ],
        'KodiCMS\CMS\Events\BackendSettingsSave' => [
            'KodiCMS\CMS\Listeners\BackendSettingsSaveListener',
        ],
        'KodiCMS\CMS\Events\NotificationCreated' => [

        ],
    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher $events
     *
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);

        $events->listen('view.settings.bottom', function () {
            WYSIWYG::loadAllEditors();
            echo view('cms::ace.settings')->with('availableACEThemes', config('cms.wysiwyg.ace_themes'));
        });

        $this->registerDatabaseConfig($events);
        $this->registerDatabaseProfiler();
    }

    private function registerDatabaseProfiler()
    {
        \DB::listen(function ($query) {
            $sql = str_replace(['%', '?'], ['%%', '%s'], $query->sql);
            $sql = vsprintf($sql, $query->bindings);

            Profiler::append('Database '.$query->connectionName, $sql, $query->time / 1000);
        });
    }

    /**
     * @param \Illuminate\Contracts\Events\Dispatcher $events
     */
    private function registerDatabaseConfig($events)
    {
        $events->listen('config.loaded', function () {
            if (cms_installed()) {
                try {
                    $databaseConfig = new \KodiCMS\CMS\Helpers\DatabaseConfig();
                    $this->app->instance('config.database', $databaseConfig);

                    $config = $databaseConfig->getAll();
                    foreach ($config as $group => $data) {
                        Config::set($group, array_merge(Config::get($group, []), $data));
                    }
                } catch (PDOException $e) {
                }
            }
        }, 999);
    }
}
