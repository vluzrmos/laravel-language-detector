<?php

return [
    /*
     * Indicates whenever should autodetect and apply the language of the request.
     */
    'autodetect' => env('LANG_DETECTOR_AUTODETECT', true),

    /*
     * Default driver to use to detect the request language.
     *
     * Available: browser, subdomain, uri.
     */
    'driver' => env('LANG_DETECTOR_DRIVER', 'browser'),

    /*
     * Used on subdomain and uri drivers. That indicates which segment should be used
     * to verify the language.
     */
    'segment' => env('LANG_DETECTOR_SEGMENT', 0),

    /*
     * Languages available on the application.
     *
     * You could use parse_langs_to_array to use the string syntax
     * or just use the array of languages with its aliases.
     */
    'languages' => parse_langs_to_array(
        env('LANG_DETECTOR_LANGUAGES', ['en'])
    ),
];
