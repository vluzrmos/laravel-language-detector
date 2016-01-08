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
        $this->registerEncryptCookies();
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
     * Disable cookie encryption for language cookie name.
     */
    protected function registerEncryptCookies()
    {
        $this->app->resolving('Illuminate\Cookie\Middleware\EncryptCookies', function ($middleware) {
            if ($this->config('cookie', true) && ! $this->config('cookie_encrypt', false)) {
                $middleware->disableFor($this->config('cookie_name', 'locale'));
            }
        });
    }

    /**
     * Register and publish configuration files.
     *
     * @return void
     */
    protected function registerAndPublishConfigurations()
    {
        $configFile = __DIR__.'/../../config/lang-detector.php';

        $this->publishes([$configFile => base_path('config/lang-detector.php')]);

        $this->mergeConfigFrom($configFile, 'lang-detector');
    }

    /**
     * Register All drivers available.
     *
     * @return void
     */
    protected function registerAllDrivers()
    {
        $languages = $this->config('languages', []);

        if (empty($languages) || in_array('auto', $languages)) {
            $languages = $this->getSupportedLanguages();

            $this->app['config']->set('lang-detector.languages', $languages);
        }

        $segment = $this->config('segment', 0);

        foreach ($this->drivers as $short => $driver) {
            $this->app->singleton(
                'language.driver.'.$short,
                function () use ($driver, $languages, $segment) {
                    /** @var AbstractDetector $instance */
                    $instance = new $driver($this->request, $languages);
                    $instance->setDefaultSegment($segment);

                    return $instance;
                }
            );
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

        $cookie = $this->config('cookie', true) ? $this->config('cookie_name', 'locale') : null;

        $driver = $this->config('driver', 'browser');

        $this->app->singleton(
            $contract,
            function () use ($driver, $cookie) {
                $detector = new LanguageDetector(
                    $this->translator,
                    $this->app['language.driver.'.$driver]
                );

                if ($cookie) {
                    $detector->useCookies($cookie);
                }

                if (method_exists($this->app, 'setLocale')) {
                    $detector->addCallback(function ($locale) {
                        $this->app->setLocale($locale);
                    });
                }

                return $detector;
            }
        );

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
     * Get language.detector from container.
     *
     * @return LanguageDetector
     */
    protected function getLanguageDetector()
    {
        return $this->app['language.detector'];
    }

    /**
     * Regiter in container the routePrefix.
     *
     * @return void
     */
    protected function registerRoutePrefix()
    {
        $this->app->bind(
            'language.routePrefix',
            function () {
                return $this->getLanguageDetector()->routePrefix();
            }
        );
    }

    /**
     * Get a list of supported locales.
     */
    protected function getSupportedLanguages()
    {
        /** @var \Illuminate\Cache\Repository $cache */
        $cache = $this->app['cache'];

        return $cache->rememberForever('lang-detector.supported-languages', function () {
            $iterator = \Symfony\Component\Finder\Finder::create()
                ->directories()
                ->in($this->app->langPath())
                ->depth(0);

            $langs = [];

            foreach ($iterator as $dir) {
                $langs[] = $dir->getBasename();
            }

            return parse_langs_to_array($langs);
        });
    }
}
