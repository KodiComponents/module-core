<?php

namespace KodiCMS\CMS\Http\Controllers\System;

use KodiCMS\CMS\Breadcrumbs\Collection as Breadcrumbs;
use KodiCMS\CMS\Exceptions\PermissionsException;
use Meta;
use UI;

class BackendController extends TemplateController
{
    /**
     * @var Navigation
     */
    public $navigation;

    /**
     * @var Breadcrumbs
     */
    public $breadcrumbs;

    public function boot()
    {
        $this->navigation = app('navigation');

        $this->breadcrumbs = new Breadcrumbs;
    }

    public function before()
    {
        $this->breadcrumbs->add(UI::icon('home'), route('backend.dashboard'));

        if ($currentPage = $this->navigation->getCurrentPage()) {
            $this->checkAccess($currentPage);
            
            foreach ($currentPage->getPathArray() as $page) {
                $this->breadcrumbs->add($page['title'], $page['url']);
            }
        }
        
        parent::before();
    }

    public function after()
    {
        $this->template
            ->with('breadcrumbs', $this->breadcrumbs)
            ->with('navigation', $this->navigation)
            ->with('bodyId', $this->getRouterPath());

        parent::after();
    }

    /**
     * @param string      $title
     * @param string|null $url
     *
     * @return $this
     */
    protected function setTitle($title, $url = null)
    {
        $this->breadcrumbs->add($title, $url);

        return parent::setTitle($title);
    }

    public function registerMedia()
    {
        parent::registerMedia();

        $this->templateScripts['ACE_THEME'] = config('cms.default_ace_theme', 'textmate');
        $this->templateScripts['DEFAULT_HTML_EDITOR'] = config('cms.default_html_editor', '');
        $this->templateScripts['DEFAULT_CODE_EDITOR'] = config('cms.default_code_editor', '');

        Meta::loadPackage('libraries', 'core');
        $this->includeModuleMediaFile($this->getRouterController());
        $this->includeMergedMediaFile('backendEvents', 'js/backendEvents');
    }

    public function checkAccess($page)
    {
        if (! $page->checkAccess()) {
            throw new PermissionsException(trans('cms::core.messages.no_access'), 403);
        }
    }
}
