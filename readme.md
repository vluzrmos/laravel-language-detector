# Laravel And Lumen Language Detector

[![Join the chat at https://gitter.im/vluzrmos/laravel-language-detector](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/vluzrmos/laravel-language-detector?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

[![Latest Stable Version](https://poser.pugx.org/vluzrmos/language-detector/v/stable)](https://packagist.org/packages/vluzrmos/language-detector)
[![Total Downloads](https://poser.pugx.org/vluzrmos/language-detector/downloads)](https://packagist.org/packages/vluzrmos/language-detector)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/vluzrmos/laravel-language-detector/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/vluzrmos/laravel-language-detector/?branch=master)
[![License](https://poser.pugx.org/vluzrmos/language-detector/license)](https://packagist.org/packages/vluzrmos/language-detector) [![Build Status](https://travis-ci.org/vluzrmos/laravel-language-detector.svg)](https://travis-ci.org/vluzrmos/laravel-language-detector) [![Code Climate](https://codeclimate.com/github/vluzrmos/laravel-language-detector/badges/gpa.svg)](https://codeclimate.com/github/vluzrmos/laravel-language-detector)

This package provides an easy way to detect and apply the user language.

# Instalation

`composer require vluzrmos/language-detector`

# Configuration

Add the service provider to your providers list:

## Laravel

Edit your `config/app.php`:

```
Vluzrmos\LanguageDetector\LanguageDetectorServiceProvider::class
```
> ::class notation is optional.

Publish the config file:

```
php artisan vendor:publish
```

## Lumen

Edit the `bootratrap/app.php`:

Lumen doesn't support vendor publish, then you have to create manualy the configuration file or
just copy the `config/lang-detector.php` to your `config/` path, then:

```php
$app->configure('lang-detector');//be sure that is before register the package

$app->register(Vluzrmos\LanguageDetector\LanguageDetectorServiceProvider::class);
```
> ::class notation is optional.

# Configuration
That is the default configuration file:

```php
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
```

Right now, only the `browser` preferences detector is available, but some awesome drivers is comming:

- Subdomain Detector
- Route Segment Detector
- TLD Domain Type (.br, .me, .pt, .us ...)

# Usage

After install & configure the package, you have to use the style `lang_LOCALE` or just `lang` on your `resources/lang` dir.
The package will try to detect the browser prefered language which matches with `lang` or `lang_LOCALE` in `config/lang-detector.php`.

```php
'languages' => ['en', 'fr', 'pt_BR' ...]
```
example:

```
├── lang
│   ├── en
│   │   ├── messages.php
│   │   └── validation.php
│   └── pt_BR
│       ├── messages.php
│       └── validation.php
```

If you are not following that style of languages names, you just configure it on `config/lang-detector.php` file:

```php
'languages' => [
    'pt_BR' => 'pt-BR', //will detect pt_BR language, and set pt-BR to the application,
    'pt' => 'pt-BR', //aliasing, will detect pt and set pt-BR to the application
    'en', //will detect 'en' language
]
```

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




