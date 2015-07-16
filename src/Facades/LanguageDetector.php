<?php

namespace Vluzrmos\LanguageDetector\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class LanguageDetector Facade.
 */
class LanguageDetector extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'language.detector';
    }
}
