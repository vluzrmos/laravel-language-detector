<?php

namespace Vluzrmos\LanguageDetector\Contracts;

/**
 * Interface ShouldPrefixRoutes.
 */
interface ShouldPrefixRoutesInterface
{
    /**
     * Return the string that should be used for prefix routes.
     *
     * @param string $locale Locale of the application.
     * @return string
     */
    public function routePrefix($locale);
}
