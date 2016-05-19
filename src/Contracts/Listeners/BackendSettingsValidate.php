<?php

namespace KodiCMS\CMS\Contracts\Listeners;

interface BackendSettingsValidate
{
    /**
     * @param \KodiCMS\CMS\Events\BackendSettingsValidate $event
     */
    public function handle(\KodiCMS\CMS\Events\BackendSettingsValidate $event);
}
