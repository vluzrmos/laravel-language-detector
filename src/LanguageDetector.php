<?php

namespace Vluzrmos\LanguageDetector;

use Symfony\Component\Translation\TranslatorInterface as Translator;
use Vluzrmos\LanguageDetector\Contracts\DetectorDriverInterface;
use Vluzrmos\LanguageDetector\Contracts\LanguageDetectorInterface;

/**
 * Class LanguageDetector.
 */
class LanguageDetector implements LanguageDetectorInterface
{
    /**
     * Translator instace.
     * @var Translator
     */
    protected $translator;

    /**
     * Driver to detect and apply the language.
     * @var DetectorDriverInterface
     */
    protected $driver;

    /**
     * @param Translator              $translator
     * @param DetectorDriverInterface $driver
     */
    public function __construct(Translator $translator, DetectorDriverInterface $driver = null)
    {
        $this->translator = $translator;
        $this->driver = $driver;
    }

    /**
     * Detect and apply the detected language.
     *
     * @return string|null Returns the detected locale or null.
     */
    public function detectAndApply()
    {
        $language = $this->detect();

        $this->apply($language);
    }

    /**
     * Detect the language.
     *
     * @return string
     */
    public function detect()
    {
        return $this->getDriver()->detect();
    }

    /**
     * Get the driver.
     * @return DetectorDriverInterface
     */
    public function getDriver()
    {
        return $this->driver;
    }

    /**
     * Set driver to detect language.
     *
     * @param DetectorDriverInterface $driver
     */
    public function setDriver(DetectorDriverInterface $driver)
    {
        $this->driver = $driver;
    }

    /**
     * Set locale to the application.
     *
     * @param string $locale
     * @return mixed
     */
    public function apply($locale)
    {
        $this->translator->setLocale($locale);
    }
}
