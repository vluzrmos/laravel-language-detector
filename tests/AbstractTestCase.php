<?php

namespace Vluzrmos\LanguageDetector;

use Illuminate\Http\Request;
use Mockery;
use Orchestra\Testbench\TestCase as Testbench;
use Symfony\Component\Translation\TranslatorInterface as Translator;

/**
 * Class TestCase
 * @package Vluzrmos\LanguageDetector
 */
abstract class AbstractTestCase extends Testbench
{
    /**
     * @param string $current Current configured Localed
     * @param null  $acceptLanguageHeader
     * @param array $config
     *
     * @return LanguageDetector
     */
    public function createInstance($current = 'en', $acceptLanguageHeader = null, $config = ['en', 'pt-BR'])
    {
        /** @var \Illuminate\Http\Request $request */
        $request = Request::create('http://localhost:8000', 'GET', [], [], [], [
            'HTTP_ACCEPT_LANGUAGE' => $acceptLanguageHeader ?: 'pt-BR,pt;q=0.8,en-US;q=0.6,en;q=0.4',
        ]);

        // create a translator mock
        $translator = Mockery::mock(Translator::class);
        $translator->shouldReceive('setLocale')->with($current)->andReturn(true);
        $translator->shouldReceive('getLocale')->andReturn($current);

        $this->app['translator'] = $translator;

        /** @var \Negotiation\LanguageNegotiator $negotiator */
        $negotiator = $this->app['language.negotiator'];

        return new LanguageDetector($request, $translator, $negotiator, $config);
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

    /**
     * @param \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return ['Vluzrmos\LanguageDetector\LanguageDetectorServiceProvider'];
    }
}
