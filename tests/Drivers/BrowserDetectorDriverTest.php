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
    public function testDetectEnglishAndApply()
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
    public function testDetectWithAliases()
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

    /**
     * Assert detection with content negotiation.
     */
    public function testDetectWithMultiSubLocalesAndContentNegotiation()
    {
        $request = $this->createRequest('http://localhost:8000', 'GET', 'zh-CN; q=0.8,en; q=0.5');

        $driver = new BrowserDetectorDriver($request, ['en', 'zh_CN' => 'zh-CN']);

        $locale = $driver->detect();

        $this->assertEquals('zh-CN', $locale);
    }
}
