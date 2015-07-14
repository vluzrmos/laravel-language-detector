<?php

namespace Vluzrmos\LanguageDetector;

use Mockery;

/**
 * Class LanguageDetectorServiceProviderTest.
 */
class LanguageDetectorServiceProviderTest extends AbstractTestCase
{
    /**
     * @return void
     */
    public function testServiceContainer()
    {
        $this->registerServiceProvider();

        $contract = 'Vluzrmos\LanguageDetector\Contracts\LanguageDetector';
        $implementation = 'Vluzrmos\LanguageDetector\LanguageDetector';
        $alias = 'language.detector';

        $this->assertInstanceOf($implementation, $this->app[$alias]);

        $this->assertInstanceOf($implementation, $this->app[$contract]);

        $this->assertInstanceOf($contract, $this->app[$contract]);
    }

    /**
     * @return void
     */
    public function registerServiceProvider()
    {
        $this->app->register('Vluzrmos\LanguageDetector\LanguageDetectorServiceProvider');
    }

    /**
     * @return void
     */
    public function testDefaultConfiguration()
    {
        $this->registerServiceProvider();

        $config = $this->app['config']->get('lang-detector.languages', null);

        $this->assertNotEmpty($config);

        $this->assertEquals(['en'], $config);
    }

    /**
     * @return void
     */
    public function testShouldNotCallDetectMethod()
    {
        $translator = $this->app['translator'];

        $translator->setLocale('fr');

        $this->assertEquals('fr', $translator->getLocale());

        $this->app['config']->set('lang-detector.autodetect', false);

        $this->registerServiceProvider();

        $this->assertEquals('fr', $translator->getLocale());
    }

    /**
     * @return void
     */
    public function testShouldCallDetectMethod()
    {
        $translator = $this->app['translator'];

        $translator->setLocale('fr');

        $this->assertEquals('fr', $translator->getLocale());

        $this->registerServiceProvider();

        $this->assertEquals('en', $translator->getLocale());
    }

    /**
     * @return void
     */
    public function testShouldDetectLanguage()
    {
        $translator = $this->app['translator'];

        $translator->setLocale('fr');

        $this->assertEquals('fr', $translator->getLocale());

        $this->registerServiceProvider();

        $this->app['language.detector']->detect();

        $this->assertEquals('en', $translator->getLocale());
    }
}
