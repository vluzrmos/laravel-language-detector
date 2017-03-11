<?php

namespace Vluzrmos\LanguageDetector;

use Closure;
use Illuminate\Contracts\Translation\Translator as IlluminateTranslator;
use Symfony\Component\Translation\TranslatorInterface as SymfonyTranslator;
use Vluzrmos\LanguageDetector\Contracts\DetectorDriverInterface as Driver;
use Vluzrmos\LanguageDetector\Contracts\LanguageDetectorInterface;
use Vluzrmos\LanguageDetector\Contracts\ShouldPrefixRoutesInterface as ShouldPrefixRoute;

/**
 * Class LanguageDetector.
 */
class LanguageDetector implements LanguageDetectorInterface
{
    /**
     * Translator instance.
     * @var SymfonyTranslator|IlluminateTranslator
     */
    protected $translator;

    /**
     * Driver to detect and apply the language.
     * @var Driver
     */
    protected $driver;

    /**
     * @var array
     */
    protected $callbacks = [];

    /**
     * Indicates cookie name or false to do not use cookies.
     * @var string|false|null
     */
    protected $cookie;

    /**
     * @param SymfonyTranslator|IlluminateTranslator $translator
     * @param Driver     $driver
     */
    public function __construct($translator, Driver $driver = null)
    {
        if (!$translator instanceof SymfonyTranslator && !$translator instanceof IlluminateTranslator) {
            throw new \InvalidArgumentException("Translator must implement the 'Symfony\\Component\\Translation\\TranslatorInterface' or 'Illuminate\\Contracts\\Translation\\Translator' interface.");
        }

        $this->translator = $translator;
        $this->driver = $driver;
    }

    /**
     * Detect and apply the detected language.
     *
     * @return string|null Returns the detected locale or null.
     */
    public function detectAndApply()
    {
        $language = $this->detect();

        if ($language) {
            $this->apply($language);
        }

        return $language;
    }

    /**
     * Detect the language.
     *
     * @return string
     */
    public function detect()
    {
        return $this->getLanguageFromCookie() ?: $this->getDriver()->detect();
    }

    /**
     * @return string
     */
    public function getLanguageFromCookie()
    {
        if ($this->cookie) {
            /** @var \Illuminate\Http\Request $request */
            $request = $this->getDriver()->getRequest();

            return $request->cookie($this->cookie);
        }

        return false;
    }

    /**
     * Add cookie with locale to queue.
     *
     * @param string $locale
     */
    public function addCookieToQueue($locale)
    {
        if ($this->cookie) {
            /** @var \Illuminate\Cookie\CookieJar $cookieJar */
            $cookieJar = cookie();

            $cookieJar->queue($cookieJar->forever($this->cookie, $locale));
        }
    }

    /**
     * Determine if should use cookies.
     * false or null will disable feature, string will set cookie name.
     *
     * @param string|bool|null $cookieName
     */
    public function useCookies($cookieName = 'locale')
    {
        $this->cookie = empty($cookieName) ? false : $cookieName;
    }

    /**
     * Get the driver.
     *
     * @return Driver
     */
    public function getDriver()
    {
        return $this->driver;
    }

    /**
     * Set driver to detect language.
     *
     * @param Driver $driver
     */
    public function setDriver(Driver $driver)
    {
        $this->driver = $driver;
    }

    /**
     * Set locale to the application.
     *
     * @param string $locale
     */
    public function apply($locale)
    {
        $this->translator->setLocale($locale);

        if (!$this->getLanguageFromCookie()) {
            $this->addCookieToQueue($locale);
        }

        $this->applyCallbacks($locale);
    }

    /**
     * Add a callback to call after applying the detected locale.
     * @param Closure $callback
     */
    public function addCallback(Closure $callback)
    {
        $this->callbacks[] = $callback;
    }

    /**
     * Call all registered callbacks.
     *
     * @param string $language
     */
    protected function applyCallbacks($language)
    {
        foreach ($this->callbacks as $callback) {
            call_user_func($callback, $language, $this);
        }
    }

    /**
     * Get the route prefix.
     *
     * @return string
     */
    public function routePrefix()
    {
        $driver = $this->getDriver();

        if ($driver instanceof ShouldPrefixRoute) {
            return $driver->routePrefix($this->translator->getLocale());
        }

        return '';
    }
}
