<?php

namespace Vluzrmos\LanguageDetector\Contracts;

use Illuminate\Http\Request;

/**
 * DetectorDriverInterface.
 */
interface DetectorDriverInterface
{
    /**
     * Return detected language.
     *
     * @return string
     */
    public function detect();
}
