<?php

namespace KodiCMS\CMS\Contracts\Listeners;

interface BackendSettingsSave
{
    /**
     * @param \KodiCMS\CMS\Events\BackendSettingsSave $event
     */
    public function handle(\KodiCMS\CMS\Events\BackendSettingsSave $event);
}
