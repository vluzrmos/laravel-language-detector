<?php

namespace Vluzrmos\LanguageDetector\Drivers;

/**
 * Class BrowserDetectorDriver.
 */
class BrowserDetectorDriver extends AbstractDetector
{
    /**
     * Detect language.
     *
     * @return string|null Returns the detected locale or null.
     */
    public function detect()
    {
        $accept = $this->chooseBestLanguage();

        $language = $accept ? $this->getAliasedLocale($accept) : null;

        return $language;
    }

    /**
     * Get accept languages.
     *
     * @return array
     */
    public function browserLanguages()
    {
        return $this->request->getLanguages();
    }

    /**
     * Get the best language between the browser and the application.
     *
     * @return array|null
     */
    public function chooseBestLanguage()
    {
        $accepted = array_intersect($this->browserLanguages(), $this->appLanguages());

        return $accepted ? array_shift($accepted) : null;
    }
}
