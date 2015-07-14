<?php

return [
    /*
     * Indicates whenever should autodetect and apply the language of the request.
     */
    'autodetect' => true,

    /*
     * Default driver to use to detect the request language.
     */
    'default_driver' => 'browser',

    /*
     * Drivers that should be loaded.
     */
    'drivers' => [
        'browser' => 'Vluzrmos\LanguageDetector\Drivers\BrowserDetectorDriver',
    ],

    /*
     * Languages available on the application.
     */
    'languages' => ['en'],
];
