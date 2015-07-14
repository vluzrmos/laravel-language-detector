<?php

namespace Vluzrmos\LanguageDetector\Contracts;

use Illuminate\Http\Request;

/**
 * DetectorDriverInterface.
 */
interface DetectorDriverInterface
{
    /**
     * @param Request $request
     * @param array   $languages
     */
    public function __construct(Request $request, array $languages);

    /**
     * Return detected language.
     * @return string
     */
    public function detect();
}
