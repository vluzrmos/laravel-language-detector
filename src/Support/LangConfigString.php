<?php

namespace Vluzrmos\LanguageDetector\Support;

/**
 * Class LangConfigString.
 */
class LangConfigString
{
    /**
     * Parse a simple string comma-separated to array. It also allow to use simple
     * indexes, like: "something:awesome, locale:pt-br, country:brazil".
     *
     * @param string $str
     *
     * @return array
     */
    public function toArray($str)
    {
        if (is_array($str)) {
            return $str;
        }

        $items = preg_split('/\s*,\s*/', $str);

        $array = [];

        foreach ($items as $item) {
            $pairs = preg_split('/\s*(:=|:|=>|=)\s*/', $item);

            if (count($pairs) == 2) {
                $array[$pairs[0]] = $pairs[1];
            } else {
                $array[] = $pairs[0];
            }
        }

        return $array;
    }
}
