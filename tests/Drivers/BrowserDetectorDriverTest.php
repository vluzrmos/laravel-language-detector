<?php

namespace Vluzrmos\LanguageDetector\Testing\Drivers;

use Vluzrmos\LanguageDetector\Drivers\BrowserDetectorDriver;

/**
 * Class BrowserDetectorDriverTest.
 */
class BrowserDetectorDriverTest extends AbstractDriversTestCase
{
    /**
     * Testing detection of default browser preferences. (default is en, en_US).
     */
    public function testShouldDetectWithBrowserDetectorDriver()
    {
        $request = $this->createRequest();

        $driver = new BrowserDetectorDriver($request, ['en']);

        $locale = $driver->detect();

        $this->assertEquals('en', $locale);

        $this->assertNotEquals('en', $this->translator->getLocale());

        $this->detector->setDriver($driver);

        $this->detector->detectAndApply();

        $this->assertEquals('en', $this->translator->getLocale());
    }

    /**
     * Testing detection of default browser preferences and aliases. (default is en, en_US).
     */
    public function testShouldDetectWithBrowserDetectorDriverAndAliases()
    {
        $request = $this->createRequest();

        $driver = new BrowserDetectorDriver($request, ['en' => 'en_US', 'pt-br']);

        $locale = $driver->detect();

        $this->assertEquals('en_US', $locale);

        $this->assertNotEquals('en_US', $this->translator->getLocale());

        $this->detector->setDriver($driver);

        $this->detector->detectAndApply();

        $this->assertEquals('en_US', $this->translator->getLocale());
    }
}
