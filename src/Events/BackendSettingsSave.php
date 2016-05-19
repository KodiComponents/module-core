<?php

namespace KodiCMS\CMS\Events;

class BackendSettingsSave
{
    /**
     * @var array
     */
    private $settings;

    /**
     * BackendSettingsHandler constructor.
     *
     * @param array $settings
     */
    public function __construct(array $settings)
    {
        $this->settings = $settings;
    }

    /**
     * @return array
     */
    public function settings()
    {
        return $this->settings;
    }
}
