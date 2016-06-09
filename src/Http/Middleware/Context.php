<?php

namespace KodiCMS\CMS\Http\Middleware;

use Closure;
use CMS;

class Context
{

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     *
     * @param string|null $context
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $context = null)
    {
        if (is_null($context)) {
            $context = ($request->is(backend_url_segment()) or $request->is(backend_url_segment().'/*'))
                ? \KodiCMS\CMS\CMS::CONTEXT_BACKEND
                : \KodiCMS\CMS\CMS::CONTEXT_FRONTEND;
        }

        $contexts = explode('|', $context);

        foreach ($contexts as $context) {
            CMS::setContext($context);
        }

        return $next($request);
    }
}
