<?php

namespace Vluzrmos\LanguageDetector;

use Illuminate\Support\ServiceProvider;

/**
 * Class LanguageDetectorServiceProvider.
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
        $contract = 'Vluzrmos\LanguageDetector\Contracts\LanguageDetector';

        $this->app->singleton($contract, function () {
            return new LanguageDetector(
                $this->app['request'],
                $this->app['translator'],
                $this->app['config']->get('lang-detector.languages', ['en'])
            );
        });

        $this->app->alias($contract, 'language.detector');

        if ($this->app['config']->get('lang-detector.autodetect', true)) {
            $this->app[$contract]->detect(true);
        }
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
