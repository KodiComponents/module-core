<?php

namespace KodiCMS\Support\Model\Fields;

use Form;

class FileField extends KodiCMSField
{
    /**
     * @param string $name
     * @param mixed  $value
     * @param array  $attributes
     *
     * @return mixed
     */
    protected function getFormFieldHTML($name, $value, array $attributes)
    {
        return Form::file($name);
    }
}
