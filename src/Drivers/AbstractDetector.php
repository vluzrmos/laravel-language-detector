<?php

namespace Vluzrmos\LanguageDetector\Drivers;

use Illuminate\Http\Request;
use Vluzrmos\LanguageDetector\Contracts\DetectorDriverInterface;

/**
 * Class AbstractDetectorDriver.
 */
abstract class AbstractDetector implements DetectorDriverInterface
{
    /**
     * Illuminate (Laravel or Lumen) Request.
     *
     * @var Request
     */
    protected $request;

    /**
     * Available Languages.
     *
     * @var array
     */
    protected $languages;

    /**
     * Browser Language Detector.
     *
     * @param Request $request   The request.
     * @param array   $languages array of available languages.
     */
    public function __construct(Request $request, array $languages)
    {
        $this->request = $request;
        $this->languages = $languages;
    }

    /**
     * Get the languages for the application.
     *
     * @return array
     */
    public function appLanguages()
    {
        $languages = [];

        foreach ($this->languages as $key => $value) {
            $languages[] = $this->keyOrValue($key, $value);
        }

        return $languages;
    }

    /**
     * Get the $value if key is numeric or null, otherwise will return the key.
     *
     * @param string|int $key
     * @param mixed      $value
     *
     * @return mixed
     */
    public function keyOrValue($key, $value)
    {
        if (is_numeric($key) or empty($key)) {
            return $value;
        }

        return $key;
    }

    /**
     * Return the real locale based on available languages.
     *
     * @param string $locale
     * @return mixed
     */
    public function getAliasedLocale($locale)
    {
        return isset($this->languages[$locale]) ? $this->languages[$locale] : $locale;
    }
}
