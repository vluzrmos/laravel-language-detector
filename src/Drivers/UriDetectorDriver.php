<?php

namespace Vluzrmos\LanguageDetector\Drivers;

/**
 * Class UriDetectorDriver.
 */
class UriDetectorDriver extends AbstractDetector
{
    /**
     * Return detected language.
     *
     * @return string
     */
    public function detect()
    {
        $parts = array_filter(preg_split('/\//', $this->request->path()));

        return count($parts) >= 1 ? $this->getAliasedLocale($parts[$this->getSegment()]) : null;
    }
}
