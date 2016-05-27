<?php

namespace KodiCMS\CMS\Navigation;

use KodiComponents\Navigation\Contracts\PageInterface;

class Navigation extends \KodiComponents\Navigation\Navigation
{

    /**
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function render()
    {
        return parent::render('cms::navigation.navigation');
    }

    /**
     * @return $this
     */
    public function filterByAccessRights()
    {
        parent::filterByAccessRights();
        $this->filterEmptyPages();

        return $this;
    }

    protected function filterEmptyPages()
    {
        $this->items = $this->getPages()->filter(function(PageInterface $page) {
            $page->filterEmptyPages();
            return !(is_null($page->getUrl()) and ! $page->hasChild());
        });
    }
}