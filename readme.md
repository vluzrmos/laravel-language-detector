# Laravel And Lumen Locale Detector

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


