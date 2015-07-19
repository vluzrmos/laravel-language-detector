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
        $bestLanguage = $this->chooseBestLanguage();

        return $this->getAliasedLocale($bestLanguage);
    }

    /**
     * Get the best language between the browser and the application.
     *
     * @return string
     */
    public function chooseBestLanguage()
    {
        return $this->request->getPreferredLanguage($this->getLanguages());
    }

    /**
     * Get accept languages.
     *
     * @deprecated
     *
     * @return array
     */
    public function browserLanguages()
    {
        return $this->request->getLanguages();
    }
}
