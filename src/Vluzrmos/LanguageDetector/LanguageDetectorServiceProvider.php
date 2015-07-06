<?php

namespace Vluzrmos\LanguageDetector;

use Illuminate\Support\ServiceProvider;

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
        $config = $this->configFile();

        $this->publishes([
            $config => base_path('config/lang-detector.php'),
        ]);

        $this->mergeConfigFrom($config, 'lang-detector');

        $this->registerLanguageDetector();
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
                $this->app['config']->get('lang-detector.languages', ['en'])
            );
        });

        $this->app['language.detector']->detect();
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
