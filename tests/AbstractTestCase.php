<?php

namespace Vluzrmos\LanguageDetector;

use Illuminate\Http\Request;
use Orchestra\Testbench\TestCase as Testbench;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class TestCase.
 */
abstract class AbstractTestCase extends Testbench
{
    /**
     * @param string $current              Current configured Localed.
     * @param string $acceptLanguageHeader
     * @param array  $config
     *
     * @return LanguageDetector
     */
    public function createInstance($current = 'en', $acceptLanguageHeader = null, $config = ['pt_BR', 'en'])
    {
        /** @var \Illuminate\Http\Request $request */
        $request = Request::create('http://localhost:8000', 'GET', [], [], [], [
            'HTTP_ACCEPT_LANGUAGE' => $acceptLanguageHeader ?: 'pt-BR,pt;q=0.8,en-US;q=0.6,en;q=0.4',
        ]);

        /*
         * Translator Mock.
         * @var TranslatorInterface
         */
        $translator = $this->app['translator'];

        $translator->setLocale($current);

        return new LanguageDetector($request, $translator, $config);
    }

    /**
     * @param string $locale
     */
    public function setAppLocale($locale)
    {
        $this->app['translator']->setLocale($locale);
    }

    /**
     * @return string
     */
    public function getAppLocale()
    {
        return $this->app['translator']->getLocale();
    }
}
