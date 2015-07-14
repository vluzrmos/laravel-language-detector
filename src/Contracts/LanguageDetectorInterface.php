<?php

namespace Vluzrmos\LanguageDetector\Contracts;

/**
 * LanguageDetectorInterface.
 */
interface LanguageDetectorInterface
{
    /**
     * Detect and apply the browser locale with matches with configurations.
     *
     * @return string
     */
    public function detectAndApply();

    /**
     * Detect the browser locale with matches with configurations.
     * @return string
     */
    public function detect();

    /**
     * Apply the locale to the application.
     * @param string $locale
     */
    public function apply($locale);

    /**
     * Set driver to detect language.
     *
     * @param DetectorDriverInterface $driver
     */
    public function setDriver(DetectorDriverInterface $driver);

    /**
     * Get the driver.
     * @return DetectorDriverInterface
     */
    public function getDriver();
}
