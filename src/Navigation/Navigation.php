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
        $this->findActive();
        $this->filterByAccessRights();
        $this->sort();

        return view('cms::navigation.navigation', [
            'pages' => $this->toArray(),
        ])->render();
    }

    /**
     * @param array $data
     *
     * @return PageInterface
     */
    protected function createPageFromArray(array $data)
    {
        $page = app(PageInterface::class);

        foreach ($data as $key => $value) {
            if ($key != 'children' and method_exists($page, $method = 'set'.ucfirst($key))) {
                $page->{$method}($value);
            }
        }

        if (isset($data['children']) and is_array($data['children'])) {
            foreach ($data['children'] as $child) {
                $page->addPage($child);
            }
        }

        return $page;
    }
}