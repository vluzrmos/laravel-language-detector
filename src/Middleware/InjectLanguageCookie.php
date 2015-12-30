<?php

namespace Vluzrmos\LanguageDetector\Middleware;

use Closure;

/**
 * Class InjectLanguageCookie.
 */
class InjectLanguageCookie
{
    /**
     * @param mixed $request
     * @param Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /** @var \Illuminate\Http\Response $response */
        $response = $next($request);

         $cookie = cookie()->forever(
            config('lang-detector.cookie_name', 'locale'),
            app('translator')->getLocale()
        );

        return $response->withCookie($cookie);
    }
}
