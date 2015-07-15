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

        // TODO: Allow specify the segment of the hostname which contains the locale.
        return count($parts) >= 3 ? $this->getAliasedLocale($parts[0]) : null;
    }
}
