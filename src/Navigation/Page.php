<?php

namespace KodiCMS\CMS\Navigation;

use KodiComponents\Navigation\Contracts\PageInterface;

class Page extends \KodiComponents\Navigation\Page
{

    /**
     * @return string
     */
    public function getId()
    {
        if (is_null($this->id)) {
            return md5($this->title.$this->getLevel());
        }

        return $this->id;
    }

    /**
     * @var array
     */
    protected $permissions = [];

    /**
     * @param array $permissions
     *
     * @return $this
     */
    public function setPermissions($permissions)
    {
        if (! is_array($permissions)) {
            $permissions = func_get_args();
        }

        $this->permissions = $permissions;

        return $this;
    }

    /**
     * @return bool
     */
    public function getAccessLogic()
    {
        if (! empty($this->permissions)) {
            foreach ($this->permissions as $permission) {
                if (\BackendGate::allows($permission)) {
                    return true;
                }
            }

            return false;
        }

        return true;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        if (is_null($this->title)) {
            return;
        }

        return trans($this->title);
    }

    /**
     * @param string $icon
     *
     * @return $this
     */
    public function setIcon($icon)
    {
        parent::setIcon('menu-icon fa fa-'.$icon);

        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        if ($this->isActive() and ! $this->hasClassProperty(['active', 'open'])) {
            $this->setHtmlAttribute('class', 'active open');
        }

        if ($this->hasChild() and ! $this->hasClassProperty('mm-dropdown')) {
            $this->setHtmlAttribute('class', 'mm-dropdown');
        }

        return parent::toArray();
    }

    /**
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function render()
    {
        return parent::render('cms::navigation.page');
    }
}