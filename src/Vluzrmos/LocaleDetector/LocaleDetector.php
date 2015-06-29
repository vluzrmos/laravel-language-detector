<?php

namespace Vluzrmos\LocaleDetector;

use Illuminate\Http\Request;
use Symfony\Component\Translation\TranslatorInterface;

class LocaleDetector
{
    /**
     * Illuminate Request.
     * @var Request
     */
    protected $request;

    /**
     * Languages dir.
     * @var string
     */
    protected $langDir;

    /**
     * Detector for the locales.
     *
     * @param Request $request
     * @param string  $langDir
     */
    public function __construct(Request $request, TranslatorInterface $translator, $langDir)
    {
        $this->request = $request;
        $this->langDir = $langDir;
        $this->translator = $translator;
    }
    
    /**
     * Get the languages for the application
     *
     * @return array
     */
    public function appLanguages()
    {
        $languages = glob($this->langDir."/*", GLOB_ONLYDIR);

        return array_map(function ($value) {
            $lang = strtolower(str_replace([$this->langDir."/", "_"], ['', '-'], $value));

            return $lang;
        }, $languages);
    }

    /**
     * Detect and apply the detected locale.
     *
     * @param  bool $apply Default true, to apply the detected locale.
     *
     * @return string|bool Returns the detected locale or false.
     */
    public function detect($apply = true)
    {
        $languages = $this->intersectLanguages();

        if (!$languages) {
            return false;
        }

        if ($apply) {
            $this->translator->setLocale($languages[0]);
        }

        return $languages[0];
    }

    /**
     * Intersect app languages and request languages.
     *
     * @return array
     */
    public function intersectLanguages()
    {
        return array_values(array_intersect(
            $this->requestLanguages(),
            $this->appLanguages()
        ));
    }
    
    /**
     * Get accept languages.
     *
     * @return array
     */
    public function requestLanguages()
    {
        $acceptLanguage = $this->request->header('Accept-Language');

        return explode(',', preg_replace('/(;q=[0-9\.]+)/i', '', strtolower(trim($acceptLanguage))));
    }
}
