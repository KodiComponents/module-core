<?php

namespace KodiCMS\CMS\Http\Controllers\API;

use KodiCMS\API\Exceptions\ValidationException;
use Validator;
use KodiCMS\API\Http\Controllers\System\Controller;
use KodiCMS\CMS\Events\BackendSettingsValidate;
use KodiCMS\CMS\Events\BackendSettingsSave;

class SettingsController extends Controller
{
    public function post()
    {
        event(new BackendSettingsValidate(
            $validator = Validator::make(
                $this->getParameter('config', []),
                []
            )
        ));

        if ($validator->fails()) {
            throw (new ValidationException())->setValidator($validator);
        }

        event(new BackendSettingsSave(
            $this->getParameter('config', [])
        ));

        $this->setMessage(trans('cms::system.messages.settings.saved'));
    }
}
