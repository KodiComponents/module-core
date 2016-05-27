<?php

namespace KodiCMS\CMS\Http;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Routing\Router;
use KodiCMS\CMS\CMS;

class Kernel extends \App\Http\Kernel
{

    /**
     * Create a new HTTP kernel instance.
     *
     * @param  \Illuminate\Contracts\Foundation\Application $app
     * @param  \Illuminate\Routing\Router $router
     *
     * @return void
     */
    public function __construct(Application $app, Router $router)
    {
        $router->middleware('context', \KodiCMS\CMS\Http\Middleware\Context::class);

        $this->middlewareGroups['backend'] = [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \KodiCMS\CMS\Http\Middleware\VerifyCsrfToken::class,
            \KodiCMS\CMS\Http\Middleware\PostJson::class,
            'backend.auth',
            'context:'.CMS::CONTEXT_BACKEND,
        ];

        parent::__construct($app, $router);
    }
}
