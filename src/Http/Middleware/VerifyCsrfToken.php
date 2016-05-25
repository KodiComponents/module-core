<?php

namespace KodiCMS\CMS\Http\Middleware;

use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Foundation\Application;

class VerifyCsrfToken extends \App\Http\Middleware\VerifyCsrfToken
{

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Foundation\Application $app
     * @param  \Illuminate\Contracts\Encryption\Encrypter $encrypter
     *
     * @return void
     */
    public function __construct(Application $app, Encrypter $encrypter)
    {
        $this->except[] = 'api.filemanager';

        parent::__construct($app, $encrypter);
    }
}
