# Laravel And Lumen Locale Detector

[![Latest Stable Version](https://poser.pugx.org/vluzrmos/locale-detector/v/stable)](https://packagist.org/packages/vluzrmos/locale-detector) [![Total Downloads](https://poser.pugx.org/vluzrmos/locale-detector/downloads)](https://packagist.org/packages/vluzrmos/locale-detector) [![Latest Unstable Version](https://poser.pugx.org/vluzrmos/locale-detector/v/unstable)](https://packagist.org/packages/vluzrmos/locale-detector) [![License](https://poser.pugx.org/vluzrmos/locale-detector/license)](https://packagist.org/packages/vluzrmos/locale-detector)

This package provides an easy way to detect and apply the user locale based on his browser configuration preferences.

# Instalation

`composer require vluzrmos/locale-detector`

# Configuration

Add the service provider to your providers list:

Laravel `config/app.php`: 

```
Vluzrmos\LocaleDetector\LocaleDetectorServiceProvider::class
```
> ::class notation is optional.

Lumen `bootratrap/app.php`:

```php
$app->register(Vluzrmos\LocaleDetector\LocaleDetectorServiceProvider::class)
```
> ::class notation is optional.


