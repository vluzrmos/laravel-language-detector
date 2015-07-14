<?php

namespace Vluzrmos\LanguageDetector\Contracts;

/**
 * Interface LanguageDetector.
 */
interface LanguageDetector
{
    /**
     * Detect the browser locale with matches with configurations and $apply if it is true.
     * @param string $apply
     * @return string
     */
    public function detect($apply);

    /**
     * Browser/Request Languages sorted by preferences.
     * @return array
     */
    public function browserLanguages();

    /**
     * Aplication available languages.
     *
     * @return array
     */
    public function appLanguages();

    /**
     * Set the locale to the application.
     *
     * @param string $locale
     * @return void
     */
    public function setLocale($locale);
}
