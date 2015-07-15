# Laravel Language Detector

[![Join the chat at https://gitter.im/vluzrmos/laravel-language-detector](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/vluzrmos/laravel-language-detector?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

[![Latest Stable Version](https://poser.pugx.org/vluzrmos/language-detector/v/stable)](https://packagist.org/packages/vluzrmos/language-detector)
[![Total Downloads](https://poser.pugx.org/vluzrmos/language-detector/downloads)](https://packagist.org/packages/vluzrmos/language-detector)
[![License](https://poser.pugx.org/vluzrmos/language-detector/license)](https://packagist.org/packages/vluzrmos/language-detector)
[![Build Status](https://travis-ci.org/vluzrmos/laravel-language-detector.svg)](https://travis-ci.org/vluzrmos/laravel-language-detector)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/vluzrmos/laravel-language-detector/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/vluzrmos/laravel-language-detector/?branch=master)
[![Code Climate](https://codeclimate.com/github/vluzrmos/laravel-language-detector/badges/gpa.svg)](https://codeclimate.com/github/vluzrmos/laravel-language-detector)
[![StyleCI](https://styleci.io/repos/38231293/shield)](https://styleci.io/repos/38231293)

This package provides an easy way to detect and apply the user language.

# Installation

Require the package with composer:

`composer require vluzrmos/language-detector`

Add the service provider to your providers list:

## Laravel

Edit your `config/app.php`:

Please, put that provider before your own `App\Providers\RouteServiceProvider`:

```
Vluzrmos\LanguageDetector\LanguageDetector\Providers\ServiceProvider::class
```
> ::class notation is optional.

Publish the config file:

```
php artisan vendor:publish
```

## Lumen

Edit the `bootratrap/app.php`:

Lumen doesn't support vendor publish, then you have to create manually the configuration file or
just copy the `config/lang-detector.php` to your `config/` path, then:

```php
$app->configure('lang-detector');//be sure that is before register the package

$app->register(Vluzrmos\LanguageDetector\Providers\LanguageDetectorServiceProvider::class);
```
> ::class notation is optional.

# Configuration
That is the default configuration file:

```php
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
```

# Detector Drivers

There are a few drivers that you might to use, choose one which matches with your application design:

## Browser Preferences
The driver `browser` will try to detect the language of the application based on the request languages (browser preferences). That driver doesn't need any other configuration, just configure the available languages, an that is it.

## Subdomains
The driver `subdomain`  will try to detect the language of the application which matches with subdomain of the hostname.
ex.: 
    
    http://fr.site.domain

The `subdomain` driver will detect `fr` language and set to the application if that is in available languages on `lang-detector` config file.

> Note: subdomain and uri drivers needs you aliases the language-locales on lang-detector config file.

## Route Prefixes 
The driver `uri` will try to detect the language based on the route prefix:

    http://site.domain/en-us/home

will detect en-us and set it to the application. (Note: consider to alias that locale)

With `uri` driver, your route group needs be like this:

```php
$prefix = app('language.routePrefix');

Route::group(['prefix' => $prefix], function () {
	// ...
});
```

# Aliasing language locales

You might to use the style `lang_LOCALE` or just `lang` on your `resources/lang` dir.
The package will try to detect the language which matches with `lang` or `lang_LOCALE` in `config/lang-detector.php`.

```php
'languages' => ['en', 'pt_BR' ...]
```
example:

```
├── lang
│   ├── en
│   │   ├── messages.php
│   │   └── validation.php
│   └── pt_BR
│       ├── messages.php
│       └── validation.php
```

If you are not following that style of languages names, or in cases you are using the `subdomain` or `uri` drivers, just configure it on `config/lang-detector.php` file:

```php
'languages' => [
    'pt_BR' => 'pt-BR', //will detect pt_BR language, and set pt-BR to the application,
    'pt' => 'pt-BR', //aliasing, will detect pt and set pt-BR to the application
    'pt-br' => "pt-BR", //aliasing, will detect pt-br and set pt-BR to the application (you will need it with subdomain driver)
    'en', //will detect 'en' language
]
```

# IoC

If you not want to always detect automatically the language, you just disable that feature on `config/lang-detector.php`:

```php
'autodetect' => false //disabling
```

And use the contract `Vluzrmos\LanguageDetector\Contracts\LanguageDetectorInterface`:

```php
use Vluzrmos\LanguageDetector\Contracts\LanguageDetectorInterface as LanguageDetector;

YourController extends Controller
{

    controllerMethod(LanguageDetector $detector)
    {
        $detector->detectAndApply();

        // or... just get the language detected, not applying
        $language = $detector->detect();
    }

}
```

or use the helper:

```php
app('language.detector')->detectAndApply();

// just getting the language detected, not applying
$language = app('language.detector')->detect();

//or

use Vluzrmos\LanguageDetector\Contracts\LanguageDetectorInterface as LanguageDetector;

app(LanguageDetector::class)->detectAndApply();

// or, just get the language detected, not applying
$language = app(LanguageDetector::class)->detect();
```


# License

MIT.
