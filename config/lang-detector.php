<?php

return [
    /*
     * Indicates whenever should autodetect and apply the language of the request.
     */
    'autodetect' => true,

    /*
     * Default driver to use to detect the request language.
     *
     * Available: browser, subdomain, uri.
     */
    'driver' => 'browser',

    /*
     * Used on subdomain and uri drivers. That indicates which segment should be used
     * to verify the language.
     */
    'segment' => 0,

    /*
     * Languages available on the application.
     */
    'languages' => ['en'],
];
