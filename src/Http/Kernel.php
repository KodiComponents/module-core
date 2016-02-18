<?php

namespace KodiCMS\CMS\Http;

use Illuminate\Routing\Router;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * Create a new HTTP kernel instance.
     *
     * @param  \Illuminate\Contracts\Foundation\Application $app
     * @param  \Illuminate\Routing\Router                   $router
     *
     * @return void
     */
    public function __construct(Application $app, Router $router)
    {
        $this->middleware[] = \KodiCMS\CMS\Http\Middleware\PostJson::class;

        if (! isset($this->routeMiddleware['backend.auth'])) {
            $this->routeMiddleware['backend.auth'] = \App\Http\Middleware\Authenticate::class;
        }

        if (! isset($this->routeMiddleware['backend.guest'])) {
            $this->routeMiddleware['backend.guest'] = \App\Http\Middleware\RedirectIfAuthenticated::class;
        }

        parent::__construct($app, $router);
    }
}
