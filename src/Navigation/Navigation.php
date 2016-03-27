<?php

namespace KodiCMS\CMS\Navigation;

class Navigation extends \KodiComponents\Navigation\Navigation
{

    /**
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function render()
    {
        $this->findActive();
        $this->filterByAccessRights();
        $this->sort();

        return view('cms::navigation.navigation', [
            'pages' => $this->toArray(),
        ])->render();
    }
}