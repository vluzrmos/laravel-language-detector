<?php

namespace Vluzrmos\LanguageDetector\Drivers;

/**
 * Class SubdomainDetectorDriver.
 */
class SubdomainDetectorDriver extends AbstractDetector
{
    /**
     * Return detected language.
     *
     * @return string
     */
    public function detect()
    {
        $parts = preg_split('/\./', $this->request->getHost());

        return count($parts) >= 3 ? $this->getAliasedLocale($parts[$this->getSegment()]) : null;
    }
}
