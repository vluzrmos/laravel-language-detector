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

        if (count($parts) >= $this->minParts) {
            $locale = $parts[$this->getDefaultSegment()];

            return $this->getAliasedLocale($locale);
        }

        return;
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
}
