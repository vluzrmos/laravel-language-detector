<?php

namespace Vluzrmos\LanguageDetector\Providers;

use Illuminate\Config\Repository;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Translation\TranslatorInterface as Translator;
use Vluzrmos\LanguageDetector\LanguageDetector;

/**
 * Class ServiceProvider.
 */
class LanguageDetectorServiceProvider extends ServiceProvider
{
    /** @var Translator $translator */
    protected $translator;

    /** @var Request $request */
    protected $request;

    /** @var Repository $config */
    protected $config;

    /**
     * Get a config value.
     * @param string $key
     * @param mixed  $default
     * @return mixed
     */
    public function config($key, $default = null)
    {
        return $this->config->get('lang-detector.'.$key, $default);
    }

    /**
     * Bootstrap the application.
     */
    public function boot()
    {
        $this->translator = $this->app['translator'];
        $this->config = $this->app['config'];
        $this->request = $this->app['request'];

        $this->registerAndPublishConfigurations();

        $this->registerDetectorDrivers();

        $this->registerLanguageDetector();

        if ($this->config('autodetect', true)) {
            /** @var LanguageDetector $detector */
            $detector = $this->app['language.detector'];
            $detector->detectAndApply();
        }
    }

    /**
     * Get the file of configurations.
     *
     * @return string
     */
    public function configFile()
    {
        return __DIR__.'/../../config/lang-detector.php';
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
     * Register and publish configuration files.
     */
    public function registerAndPublishConfigurations()
    {
        $configFile = $this->configFile();

        $this->publishes([
             $configFile => base_path('config/lang-detector.php'),
        ]);

        $this->mergeConfigFrom($configFile, 'lang-detector');
    }
    /**
     * Register the detector instance.
     *
     * @return void
     */
    public function registerLanguageDetector()
    {
        $contract = 'Vluzrmos\LanguageDetector\Contracts\LanguageDetectorInterface';

        $driver = $this->config('default_driver', 'browser');

        $this->app->singleton($contract, function () use ($driver) {
            return new LanguageDetector(
                $this->translator,
                $this->app['language.driver.'.$driver]
            );
        });

        $this->app->alias($contract, 'language.detector');
    }

    /**
     * Register drivers.
     *
     * @return void
     */
    public function registerDetectorDrivers()
    {
        $drivers = $this->config('drivers', []);

        $languages = $this->config('languages', []);

        foreach ($drivers as $shortcut => $driver) {
            $this->app->singleton('language.driver.'.$shortcut, function () use ($driver, $languages) {
                return new $driver($this->request, $languages);
            });

            $this->app->alias('language.driver.'.$shortcut, $driver);
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
