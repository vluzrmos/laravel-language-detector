<?php

namespace Vluzrmos\LanguageDetector\Drivers;

/**
 * Class SubdomainDetectorDriver.
 */
class SubdomainDetectorDriver extends AbstractDetector
{
    /**
     * Minimun parts of the subdomain.
     *
     * @var int
     */
    protected $minParts = 3;

    /**
     * Return detected language.
     *
     * @return string
     */
    public function detect()
    {
        $parts = $this->getSegments();

        $locale = null;

        if ($this->isPartsValid($parts)) {
            $locale = $parts[$this->getDefaultSegment()];
        }

        return $locale ? $this->getAliasedLocale($locale) : null;
    }

    /**
     * Get parts of the subdomain.
     *
     * @return array
     */
    public function getSegments()
    {
        return preg_split('/\./', $this->request->getHost());
    }

    /**
     * Check if parts of the url are valid.
     *
     * @param array $parts
     * @return bool
     */
    public function isPartsValid(array $parts)
    {
        return count($parts) >= $this->minParts;
    }
}
