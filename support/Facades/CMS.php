<?php

namespace KodiCMS\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class CMS
 * @package KodiCMS\Support\Facades
 */
class CMS extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'cms';
    }
}
