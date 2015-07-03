<?php

namespace Vluzrmos\LanguageDetector;

use Illuminate\Http\Request;
use Negotiation\LanguageNegotiator as Negotiator;
use Symfony\Component\Translation\TranslatorInterface as Translator;

/**
 * Class LanguageDetector
 * @package Vluzrmos\LanguageDetector
 */
class LanguageDetector
{
    /**
     * Illuminate Request.
     * @var Request
     */
    protected $request;

    /**
     * Languages.
     * @var array
     */
    protected $langs;

    /**
     * @var LanguageNegotiator
     */
    private $negotiator;

    /**
     * Browser Language Detector.
     *
     * @param Request    $request
     * @param Translator $translator
     * @param Negotiator $negotiator
     * @param array      $langs
     */
    public function __construct(Request $request, Translator $translator, Negotiator $negotiator, array $langs)
    {
        $this->request = $request;
        $this->langs = $langs;
        $this->translator = $translator;
        $this->negotiator = $negotiator;
    }

    /**
     * Detect and apply the detected language.
     *
     * @param  bool $apply Default true, to apply the detected locale.
     *
     * @return string|bool Returns the detected locale or false.
     */
    public function detect($apply = true)
    {
        $language = $this->negotiator->getBest(
            $this->browserLanguages(),
            $this->appLanguages()
        );

        if ($apply && $language) {
            $this->setLocale($language->getValue());
        }

        return $language;
    }

    /**
     * Get accept languages.
     *
     * @return array
     */
    public function browserLanguages()
    {
        return $this->request->header('Accept-Language');
    }

    /**
     * Get the languages for the application
     *
     * @return array
     */
    public function appLanguages()
    {
        $map = [];

        foreach ($this->langs as $key => $value) {
            $map[] = $this->keyOrValue($key, $value);
        }

        return $map;
    }

    /**
     * Return the value or key if key is numeric.
     *
     * @param string|integer $key
     * @param mixed          $value
     *
     * @return mixed
     */
    public function keyOrValue($key, $value)
    {
        if (is_numeric($key)) {
            return $value;
        }

        return $key;
    }

    /**
     * Set the locale.
     *
     * @param string $locale
     *
     * @return void
     */
    public function setLocale($locale)
    {
        $locale = isset($this->langs[$locale]) ? $this->langs[$locale] : $locale;

        $this->translator->setLocale($locale);
    }
}
