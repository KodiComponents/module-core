<?php

namespace KodiCMS\CMS\Events;

use Illuminate\Validation\Validator;

class BackendSettingsValidate
{
    /**
     * @var Validator
     */
    private $validator;

    /**
     * BackendSettingsHandler constructor.
     *
     * @param Validator $validator
     */
    public function __construct(Validator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @return Validator
     */
    public function validator()
    {
        return $this->validator;
    }
}
