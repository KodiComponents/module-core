<?php

namespace KodiCMS\CMS\Listeners;

use KodiCMS\CMS\Contracts\Listeners\BackendSettingsValidate;

class BackendSettingsValidateListener implements BackendSettingsValidate
{
    /**
     * @param \KodiCMS\CMS\Events\BackendSettingsValidate $event
     */
    public function handle(\KodiCMS\CMS\Events\BackendSettingsValidate $event)
    {
        
    }
}
