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
     * Default segment to use on uri and browser detectors.
     *
     * @var int
     */
    protected $segment = 0;

    /**
     * Browser Language Detector.
     *
     * @param Request $request   Current request.
     * @param array   $languages array of available languages.
     */
    public function __construct(Request $request, array $languages = [])
    {
        $this->setRequest($request);
        $this->setLanguages($languages);
    }

    /**
     * Getter to the request.
     *
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Setter to the request.
     *
     * @param Request $request
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Get the languages available for the application.
     *
     * @return array
     */
    public function getLanguages()
    {
        $languages = [];

        foreach ($this->languages as $key => $value) {
            $languages[] = $this->keyOrValue($key, $value);
        }

        return $languages;
    }

    /**
     * Set languages available to the application.
     *
     * @param array $languages
     */
    public function setLanguages(array $languages)
    {
        $this->languages = $languages;
    }

    /**
     * Set default segment value.
     *
     * @param int $segment
     */
    public function setDefaultSegment($segment = 0)
    {
        $this->segment = (int) $segment;
    }

    /**
     * Get default segment value.
     *
     * @return int
     */
    public function getDefaultSegment()
    {
        return $this->segment;
    }

    /**
     * Return the real locale based on available languages.
     *
     * @param string $locale
     * @return string|null
     */
    public function getAliasedLocale($locale)
    {
        if (isset($this->languages[$locale])) {
            return $this->languages[$locale];
        }

        return in_array($locale, $this->languages) ? $locale : null;
    }

    /**
     * Get the $value if key is numeric or null, otherwise will return the key.
     *
     * @param string|int $key
     * @param mixed      $value
     *
     * @return mixed
     */
    protected function keyOrValue($key, $value)
    {
        if (is_numeric($key) || empty($key)) {
            return $value;
        }

        return $key;
    }
}
