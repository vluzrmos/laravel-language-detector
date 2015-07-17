<?php

namespace Vluzrmos\LanguageDetector\Providers;

use Illuminate\Config\Repository as Config;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Translation\TranslatorInterface as Translator;
use Vluzrmos\LanguageDetector\Drivers\AbstractDetector;
use Vluzrmos\LanguageDetector\LanguageDetector;

/**
 * Class ServiceProvider.
 */
class LanguageDetectorServiceProvider extends ServiceProvider
{
    /**
     * Symfony translator.
     * @var Translator
     */
    protected $translator;

    /**
     * Illuminate Request.
     * @var Request
     */
    protected $request;

    /**
     * Configurations repository.
     * @var Config
     */
    protected $config;

    /**
     * Detector Drivers available and its shortcuts.
     * @var array
     */
    protected $drivers = [
        'browser' => 'Vluzrmos\LanguageDetector\Drivers\BrowserDetectorDriver',
        'subdomain' => 'Vluzrmos\LanguageDetector\Drivers\SubdomainDetectorDriver',
        'uri' => 'Vluzrmos\LanguageDetector\Drivers\UriDetectorDriver',
    ];

    /**
     * Bootstrap the application.
     *
     * @return void
     */
    public function boot()
    {
        $this->translator = $this->app['translator'];
        $this->config = $this->app['config'];
        $this->request = $this->app['request'];

        $this->registerAndPublishConfigurations();

        $this->registerAllDrivers();

        $this->registerLanguageDetector();

        $this->detectAndApplyLanguage();

        $this->registerRoutePrefix();
    }

    /**
     * Register the package.
     */
    public function register()
    {
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
            'language.driver.browser',
            'language.driver.subdomain',
            'language.driver.uri',
        ];
    }

    /**
     * Register and publish configuration files.
     *
     * @return void
     */
    protected function registerAndPublishConfigurations()
    {
        $configFile = $this->configFile();

        $this->publishes([$configFile => base_path('config/lang-detector.php')]);

        $this->mergeConfigFrom($configFile, 'lang-detector');
    }

    /**
     * Get the file of configurations.
     *
     * @return string
     */
    protected function configFile()
    {
        return __DIR__.'/../../config/lang-detector.php';
    }

    /**
     * Register All drivers available.
     *
     * @return void
     */
    protected function registerAllDrivers()
    {
        $languages = $this->config('languages', []);

        $segment = $this->config('segment', 0);

        foreach ($this->drivers as $short => $driver) {
            $this->registerDriver($short, $driver, $languages, $segment);
        }
    }

    /**
     * Get a config value.
     * @param string $key
     * @param mixed  $default
     * @return mixed
     */
    protected function config($key, $default = null)
    {
        return $this->config->get('lang-detector.'.$key, $default);
    }

    /**
     * Register the detector instance.
     *
     * @return void
     */
    protected function registerLanguageDetector()
    {
        $contract = 'Vluzrmos\LanguageDetector\Contracts\LanguageDetectorInterface';

        $driver = $this->config('driver', 'browser');

        $this->app->singleton($contract, function () use ($driver) {
            return new LanguageDetector(
                $this->translator,
                $this->app['language.driver.'.$driver]
            );
        });

        $this->app->alias($contract, 'language.detector');
    }

    /**
     * Detect and apply language for the application.
     */
    protected function detectAndApplyLanguage()
    {
        if ($this->config('autodetect', true)) {
            $this->getLanguageDetector()->detectAndApply();
        }
    }

    /**
     * Regiter in container the routePrefix.
     *
     * @return void
     */
    protected function registerRoutePrefix()
    {
        $this->app->bind('language.routePrefix', function () {
            return $this->getLanguageDetector()->routePrefix();
        });
    }

    /**
     * Get language.detector from container.
     *
     * @return LanguageDetector
     */
    protected function getLanguageDetector()
    {
        return $this->app['language.detector'];
    }

    /**
     * Register a driver on application container.
     *
     * @param string $short     Shortcut name of the driver.
     * @param string $driver    Driver namespaced.
     * @param array  $languages Array of available languages to the application.
     * @param int    $segment   Segment used in Sudomain or Uri drivers.
     *
     * @return void
     */
    protected function registerDriver($short, $driver, $languages, $segment)
    {
        $this->app->singleton('language.driver.'.$short, function () use ($driver, $languages, $segment) {
            /** @var AbstractDetector $instance */
            $instance = new $driver($this->request, $languages);
            $instance->setDefaultSegment($segment);

            return $instance;
        });
    }
}
