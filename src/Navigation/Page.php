<?php

namespace KodiCMS\CMS\Navigation;

class Page extends \KodiComponents\Navigation\Page
{

    /**
     * @param string $label
     *
     * @return $this
     */
    public function setLabel($label)
    {
        $this->setTitle(trans($label));

        return $this;
    }

    /**
     * @param string $label
     *
     * @return $this
     */
    public function setIcon($icon)
    {
        parent::setIcon('fa fa-' . $icon);

        return $this;
    }

    /**
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function render()
    {
        return view('cms::navigation.page', $this->toArray());
    }
}