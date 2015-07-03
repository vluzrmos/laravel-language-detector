<?php

namespace Vluzrmos\LanguageDetector;

use Illuminate\Support\ServiceProvider;
use Negotiation\LanguageNegotiator;

/**
 * Class LanguageDetectorServiceProvider
 * @package Vluzrmos\LanguageDetector
 */
class LanguageDetectorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            $this->configFile() => base_path('config/lang-detector.php'),
        ]);
    }

    /**
     * Get the file of configurations.
     *
     * @return string
     */
    public function configFile()
    {
        return __DIR__.'/../../../config/lang-detector.php';
    }

    /**
     * Register the package.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom($this->configFile(), 'lang-detector');

        $this->registerLanguageNegotiator();

        $this->registerLanguageDetector();

        $this->app['language.detector']->detect();
    }

    /**
     * Register the negotiator instance.
     *
     * @return void
     */
    public function registerLanguageNegotiator()
    {
        $this->app->singleton('language.negotiator', function () {
            return new LanguageNegotiator();
        });
    }

    /**
     * Register the detector instance.
     *
     * @return void
     */
    public function registerLanguageDetector()
    {
        $this->app->singleton('language.detector', function () {
            return new LanguageDetector(
                $this->app['request'],
                $this->app['translator'],
                $this->app['language.negotiator'],
                $this->app['config']->get('lang-detector.languages', ['en'])
            );
        });
    }

    /**
     * The services that package provides.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'language.detector',
            'language.negotiator',
        ];
    }
}
