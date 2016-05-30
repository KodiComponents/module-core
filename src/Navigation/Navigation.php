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
}