<?php

namespace Vluzrmos\LanguageDetector;

use Mockery;

/**
 * Class LanguageDetectorServiceProviderTest
 * @package Vluzrmos\LanguageDetector
 */
class LanguageDetectorServiceProviderTest extends AbstractTestCase
{

    /**
     * @return void
     */
    public function testServiceContainer()
    {
        $this->registerServiceProvider();

        $this->assertInstanceOf('Vluzrmos\LanguageDetector\LanguageDetector', $this->app['language.detector']);
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
    public function testShouldCallDetectMethod()
    {
        $translator = $this->app['translator'];

        $translator->setLocale('fr');

        $this->assertEquals('fr', $translator->getLocale());

        $this->registerServiceProvider();

        $this->assertEquals('en', $translator->getLocale());
    }
}
