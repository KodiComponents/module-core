<?php

namespace KodiCMS\CMS\Listeners;

use DatabaseConfig;
use KodiCMS\CMS\Contracts\Listeners\BackendSettingsSave;

class BackendSettingsSaveListener implements BackendSettingsSave
{

    /**
     * @param \KodiCMS\CMS\Events\BackendSettingsSave $event
     */
    public function handle(\KodiCMS\CMS\Events\BackendSettingsSave $event)
    {
        DatabaseConfig::save($event->settings());
    }
}
