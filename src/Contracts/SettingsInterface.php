<?php

namespace KodiCMS\CMS\Contracts;

use ArrayAccess;

interface SettingsInterface extends ArrayAccess
{
    /**
     * @return array
     */
    public function booleanSettings();

    /**
     * @return array
     */
    public function defaultSettings();

    /**
     * @return array
     */
    public function getSettings();

    /**
     * @param string $name
     * @param mixed  $default
     *
     * @return mixed
     */
    public function getSetting($name, $default = null);

    /**
     * @param string $name
     * @param mixed  $value
     *
     * @return $this
     */
    public function setSetting($name, $value = null);

    /**
     * @param array $settings
     *
     * @return $this
     */
    public function setSettings(array $settings);

    /**
     * @param array $settings
     *
     * @return $this
     */
    public function replaceSettings(array $settings);
}
