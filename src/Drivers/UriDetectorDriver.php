<?php

namespace Vluzrmos\LanguageDetector\Drivers;

use Vluzrmos\LanguageDetector\Contracts\ShouldPrefixRoutesInterface as PrefixRoutes;

/**
 * Class UriDetectorDriver.
 */
class UriDetectorDriver extends SubdomainDetectorDriver implements PrefixRoutes
{
    /**
     * Minimun parts of the uri.
     *
     * @var int
     */
    protected $minParts = 1;

    /**
     * Get the prefix for routes based on a given locale.
     *
     * @param string $locale
     * @return string
     */
    public function routePrefix($locale)
    {
        $parts = $this->getSegments();

        if ($this->isPartsValid($parts) &&
            $parts[$this->getDefaultSegment()] == $locale
        ) {
            return $locale;
        }

        $aliases = $this->getAliasesToLocale($locale);

        return $aliases ? array_shift($aliases) : '';
    }

    /**
     * Get parts of the url.
     *
     * @return array
     */
    public function getSegments()
    {
        return array_filter(preg_split('/\//', $this->request->path()));
    }

    /**
     * Array of aliases to a given locale.
     *
     * @param $locale
     * @return array
     */
    protected function getAliasesToLocale($locale)
    {
        $aliases = array_keys($this->languages, $locale);

        $parts = $this->getSegments();

        if ($this->isPartsValid($parts)) {
            $segment = $parts[$this->getDefaultSegment()];

            return array_filter(
                $aliases,
                function ($item) use ($segment) {
                    return $segment == $item;
                }
            );
        }

        return [];
    }
}
