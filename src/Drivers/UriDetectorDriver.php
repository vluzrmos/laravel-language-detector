<?php

namespace Vluzrmos\LanguageDetector\Drivers;

/**
 * Class UriDetectorDriver.
 */
class UriDetectorDriver extends SubdomainDetectorDriver
{
    /**
     * Minimun parts of the uri.
     *
     * @var int
     */
    protected $minParts = 1;

    /**
     * Get parts of the url.
     *
     * @return array
     */
    public function getSegments()
    {
        return array_filter(preg_split('/\//', $this->request->path()));
    }
}
