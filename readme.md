# Laravel And Lumen Language Detector

[![Latest Stable Version](https://poser.pugx.org/vluzrmos/locale-detector/v/stable)](https://packagist.org/packages/vluzrmos/locale-detector) [![Total Downloads](https://poser.pugx.org/vluzrmos/locale-detector/downloads)](https://packagist.org/packages/vluzrmos/locale-detector) [![Latest Unstable Version](https://poser.pugx.org/vluzrmos/locale-detector/v/unstable)](https://packagist.org/packages/vluzrmos/locale-detector) [![License](https://poser.pugx.org/vluzrmos/locale-detector/license)](https://packagist.org/packages/vluzrmos/locale-detector)

This package provides an easy way to detect and apply the user language based on his browser configuration preferences.

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

# Usage

After install & configure the package, you have to use the style `lang-LOCALE` or just `lang` on your `resources/lang` dir. 
The package will try to detect the browser prefered language which matches with `lang` or `lang-LOCALE` in `config/lang-detector.php`.

```php
return [
    'languages' => ['en', 'fr', 'pt'] //...
];
```
example:

```
├── lang
│   ├── en
│   │   ├── messages.php
│   │   └── validation.php
│   └── pt
│       ├── messages.php
│       └── validation.php
```

If you are not following that style of languages names, you just configure it on `config/lang-detector.php` file:

```php
return [
    'languages' => [
        'pt-BR' => 'pt_BR', //will detect pt-BR language, and set pt_BR to the application
        'en', //will detect 'en' language
    ]
];
```






