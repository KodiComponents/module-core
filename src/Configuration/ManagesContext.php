<?php

namespace KodiCMS\CMS\Configuration;

use Illuminate\Support\Str;

trait ManagesContext
{

    /**
     * @var array
     */
    protected $context = [];


    /**
     * @param string $context
     *
     * @return $this
     */
    public function setContext($context)
    {
        if (! $this->contextExists($context)) {
            $this->context[] = $context;

            foreach (app('modules.loader')->getRegisteredModules() as $module) {
                if (! is_null($provider = $module->getProvider())) {
                    if (method_exists($provider, $method = 'context'.Str::studly($context))) {
                        app()->call([$provider, $method]);
                    }
                }
            }
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @param string $context
     *
     * @return bool
     */
    public function contextExists($context)
    {
        return in_array($context, $this->context);
    }

}
