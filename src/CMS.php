<?php

namespace KodiCMS\CMS;

use KodiCMS\CMS\Configuration\ManagesAuthOptions;
use KodiCMS\CMS\Configuration\ManagesContext;
use KodiCMS\CMS\Configuration\ManagesModelOptions;

class CMS
{
    use ManagesModelOptions,
        ManagesContext;
    
    const VERSION = '0.5.1 beta';
    const NAME    = 'KodiCMS';
    const WEBSITE = 'http://kodicms.com';

    const CMS_PREFIX = 'cms';

    const CONTEXT_BACKEND = 'backend';
    const CONTEXT_FRONTEND = 'frontend';

    /**
     * @return string
     */
    public function getFullName()
    {
        return "{$this->getName()} v.{$this->getVersion()}";
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return static::VERSION;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return static::NAME;
    }

    /**
     * @return string
     */
    public function getWebsite()
    {
        return static::WEBSITE;
    }

    /**
     * @return string
     */
    public function getPrefix()
    {
        return static::CMS_PREFIX;
    }

    /**
     * @return bool
     */
    public function isInstalled()
    {
        return is_file(base_path(app()->environmentFile()));
    }

    /**
     * @return bool
     */
    public function isBackend()
    {
        return $this->contextExists(static::CONTEXT_BACKEND);
    }

    /**
     * @return string
     */
    public function backendUrlSegment()
    {
        return config('cms.backend_url_segment', 'backend');
    }

    /**
     * @param null|string $path
     *
     * @return string
     */
    public function backendUrl($path = null)
    {
        return url($this->backendUrlSegment().$this->trimPath($path));
    }

    /**
     * @param null|string $path
     *
     * @return string
     */
    public function resourcesUrl($path = null)
    {
        return url(static::CMS_PREFIX.$this->trimPath($path));
    }

    /**
     * @param null|string $path
     *
     * @return string
     */
    public function backendResourcesPath($path = null)
    {
        return public_path(static::CMS_PREFIX.DIRECTORY_SEPARATOR.(! is_null($path)
                ? normalize_path($path)
                : $path));
    }

    /**
     * @param null|string $path
     *
     * @return string
     */
    public function backendResourcesUrl($path = null)
    {
        return $this->backendUrl(static::CMS_PREFIX.$this->trimPath($path));
    }

    /**
     * @return $this
     */
    public function setBackendContext()
    {
        return $this->setContext(static::CONTEXT_BACKEND);
    }

    /**
     * @param string $path
     * @param string $separator
     *
     * @return string
     */
    protected function trimPath($path, $separator = '/')
    {
        return ! is_null($path)
            ? $separator.ltrim($path, $separator)
            : $path;
    }
}
