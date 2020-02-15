# Laravel Language Detector

[![Join the chat at https://gitter.im/vluzrmos/laravel-language-detector](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/vluzrmos/laravel-language-detector?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

[![Latest Stable Version](https://poser.pugx.org/vluzrmos/language-detector/v/stable)](https://packagist.org/packages/vluzrmos/language-detector)
[![Total Downloads](https://poser.pugx.org/vluzrmos/language-detector/downloads)](https://packagist.org/packages/vluzrmos/language-detector)
[![License](https://poser.pugx.org/vluzrmos/language-detector/license)](https://packagist.org/packages/vluzrmos/language-detector)

[![Build Status](https://travis-ci.org/vluzrmos/laravel-language-detector.svg)](https://travis-ci.org/vluzrmos/laravel-language-detector)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/vluzrmos/laravel-language-detector/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/vluzrmos/laravel-language-detector/?branch=master)
[![Code Climate](https://codeclimate.com/github/vluzrmos/laravel-language-detector/badges/gpa.svg)](https://codeclimate.com/github/vluzrmos/laravel-language-detector)
[![Codacy Badge](https://www.codacy.com/project/badge/f024fb197e1c4a298a537794cb077901)](https://www.codacy.com/app/vluzrmos/laravel-language-detector)
[![StyleCI](https://styleci.io/repos/38231293/shield)](https://styleci.io/repos/38231293)

This package provides an easy way to detect and apply the language for your application
using [browser preferences](#browser-preferences), [subdomains](#subdomains) or [route prefixes](#route-prefixes).

# Installation

Require the package using composer:

`composer require vluzrmos/language-detector`

Add the service provider as follows:

## Laravel

Edit your `config/app.php`:

Insert this line of code **above** the listed RouteServiceProvider, ex:

```
Vluzrmos\LanguageDetector\Providers\LanguageDetectorServiceProvider::class,
App\Providers\RouteServiceProvider::class, 
```
> ::class notation is optional.

## Lumen

Edit the `bootstrap/app.php`:

```php
$app->register(Vluzrmos\LanguageDetector\Providers\LanguageDetectorServiceProvider::class);
```
> ::class notation is optional.

# Configuration

Two options for Laravel, either publish the package conﬁguration using: 

    php artisan vendor:publish --provider="Vluzrmos\LanguageDetector\Providers\LanguageDetectorServiceProvider"

then edit the new `conﬁg/lang-detector.php` ﬁle or add the following lines to your .env ﬁle: 

```bash
#Indicates whether the language should be autodetected (can be removed)
LANG_DETECTOR_AUTODETECT=true

#Driver to use, the default is browser 
LANG_DETECTOR_DRIVER="browser"

#Segment to use in the uri or subdomain driver, default 0 (can be removed) 
LANG_DETECTOR_SEGMENT=0

#Name of cookie to cache the detected language (use false|null to disable) 
LANG_DETECTOR_COOKIE=locale

#Comma-separated list of languages provided on the app 
LANG_DETECTOR_LANGUAGES="en,fr,pt_BR"

#To alias the language use the notation ":", "=", ":=" or  "=>" to separate the alias and its value.
# LANG_DETECTOR_LANGUAGES="en, en-us:en, pt-br:pt_BR"
```

If you not want to use that, just publish the configurations of the package with
`php artisan vendor:publish` and edit on `config/lang-detector.php` generated.

**For Lumen**, consider to copy `vendor/vluzrmos/language-detector/config/lang-detector.php`
to your configs dir and use `$app->configure('lang-detector')` before register the
`LanguageDetectorServiceProvider`.

# Detector Drivers

There are a few drivers that you might to use, choose one which matches with your application design:

## Browser Preferences
The driver `browser` will try to detect the language of the application based on the request languages (browser preferences). This driver doesn't need any other configuration, just configure the available languages.

## Subdomains
The driver `subdomain`  will try to detect the language of the application which matches with subdomain of the hostname.
eg.:

    http://fr.site.domain

The `subdomain` driver will detect language `fr` and set the application to `fr`  if it is one of the  available languages in the `lang-detector` conﬁg ﬁle.

> Note: subdomain and uri drivers require [aliases](#aliasing-language-locales) of the the language-locales in the lang-detector conﬁg ﬁle.

## Route Prefixes
The driver `uri` will try to detect the language based on the route prefix:

    http://site.domain/en-us/home

That driver will detect en-us and set it to the application.
(Note: Consider to [aliase](#aliasing-language-locales) that locale)

And don't worry, if the url is like:

    http://site.domain/home

The language will not be changed, the application will use your default language configured on your `config/app.php` file.

With `uri` driver, your route group needs be like this:

```php
//That would be nice if you put (edit) it on your App\Providers\RouteServiceProvider.

// Laravel
Route::group(['prefix' => app('language.routePrefix'), 'namespace' => 'App\Http\Controllers'], function ($router) {
	require __DIR__.'/../Http/routes.php';
});

//For lumen, it should be on bootstrap/app.php.

// Lumen
$app->group(['prefix' => app('language.routePrefix'), 'namespace' => 'App\Http\Controllers'], function ($app) {
   require __DIR__.'/../app/Http/routes.php';
}
```

**Issue**: Lumen 5.0 doesn't support route prefix with empty strings, you should use
that script:

```php
$prefix = app('language.routePrefix');

$options = [];

if (!empty($prefix) && $prefix!="/") {
    $options['prefix'] = $prefix;
}

// any other options here
$options['namespace'] = 'App\Http\Controllers';

$app->group($options, function () use($app) {
	// ...
});
```

> Note: That is only for Lumen 5.0, the newest version (5.1) already fixes it.

# Aliasing language locales

You might to use the style `lang_LOCALE` or just `lang` on your `resources/lang` dir.
The language detector driver you have chosen will try to detect the language
which matches with `lang` or `lang_LOCALE` available on your `config/lang-detector.php`.

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

If you are not following that style of languages names or if you are using
the `subdomain` or `uri` drivers, just configure it on `config/lang-detector.php` file:

```php
'languages' => [
    'pt_BR' => 'pt-BR', //will detect pt_BR language, and set pt-BR to the application,
    'pt' => 'pt-BR', //aliasing, will detect pt and set pt-BR to the application
    'pt-br' => "pt-BR", //aliasing, will detect pt-br and set pt-BR to the application (you will need it with subdomain driver)
    'en', //will detect 'en' language
]
```

or if you are using `.env` instead of config file:

```
#Just put the languages in a comma-separated string.
#To aliase the language use the notation ":", "=", ":=" or  "=>" to separate the alias and its value.
LANG_DETECTOR_LANGUAGES="pt_BR:pt-BR, pt:pt-BR, pt-br:pt-BR, en"
```

# Suggestions

The default Laravel language lines are available translated into 51 languages here:

- [caouecs/laravel-lang](https://github.com/caouecs/Laravel-lang)

If you want to translate your models you can use this package: 

- [dimsav/laravel-translatable](https://github.com/dimsav/laravel-translatable)

# License

MIT.
