<?php

namespace Vluzrmos\LanguageDetector\Testing;

use Illuminate\Foundation\Application;
use Orchestra\Testbench\TestCase;

/**
 * Class LanguageDetectorServiceProviderTest.
 */
class LanguageDetectorServiceProviderTest extends TestCase
{
    /**
     * Registering the service provider.
     */
    public function testShouldRegisterServiceProvider()
    {
        $driverShortcut = $this->app['config']->get('lang-detector.default_driver');

        $this->assertInstanceOf(
            'Vluzrmos\LanguageDetector\Contracts\DetectorDriverInterface',
            $this->app['language.driver.'.$driverShortcut]
        );

        $driver = $this->app['config']->get('lang-detector.drivers.'.$driverShortcut);

        $this->assertInstanceOf($driver, $this->app[$driver]);

        $this->assertInstanceOf(
            'Vluzrmos\LanguageDetector\Contracts\LanguageDetectorInterface',
            $this->app['language.detector']
        );

        $this->assertInstanceOf(
            'Vluzrmos\LanguageDetector\Contracts\LanguageDetectorInterface',
            $this->app['Vluzrmos\LanguageDetector\Contracts\LanguageDetectorInterface']
        );
    }

    /**
     * @param Application $app
     * @return array
     */
    public function getPackageProviders($app)
    {
        return [
            'Vluzrmos\LanguageDetector\Providers\LanguageDetectorServiceProvider',
        ];
    }
}
