<?php

namespace Vluzrmos\LanguageDetector\Middleware;

use Closure;

/**
 * Class InjectLanguageCookie.
 */
class InjectLanguageCookie
{
    /**
     * @param mixed   $request
     * @param Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /** @var \Illuminate\Http\Response $response */
        $response = $next($request);

        if ($this->config('cookie', true)) {
            $cookie = cookie()->forever(
                $this->config('cookie_name', 'locale'),
                app('translator')->getLocale()
            );

            $response->withCookie($cookie);
        }

        return $response;
    }

    /**
     * @param string $key
     * @param mixed $default
     *
     * @return mixed
     */
    public function config($key, $default = null)
    {
        return config('lang-detector.'.$key, $default);
    }
}
