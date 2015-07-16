<?php

namespace Vluzrmos\LanguageDetector\Testing\Drivers;

use Illuminate\Http\Request;
use Vluzrmos\LanguageDetector\Drivers\SubdomainDetectorDriver;
use Vluzrmos\LanguageDetector\Drivers\UriDetectorDriver;

/**
 * Class UriDetectorDriverTest.
 */
class UriDetectorDriverTest extends AbstractDriversTestCase
{
    /**
     * Testing when should not change locale: hostname without subdomain.
     */
    public function testShouldNotChangeTheLocale()
    {
        $this->translator->setLocale('fr');

        /** @var Request $request */
        $request = Request::create('http://example.com');

        $uri = new UriDetectorDriver($request, ['en']);

        $locale = $uri->detect();

        $this->assertEmpty($locale);

        $this->detector->setDriver($uri);

        $this->detector->detectAndApply();

        $this->assertEquals('fr', $this->translator->getLocale());

        $prefix = $uri->routePrefix($this->translator->getLocale());

        $this->assertEmpty($prefix);
    }

    /**
     * Testing should change the locale matching if available with uri.
     */
    public function testShouldMatchesWithTheUri()
    {
        $this->translator->setLocale('fr');

        /** @var Request $request */
        $request = Request::create('http://example.com/en');

        $uri = new UriDetectorDriver($request, ['en']);

        $locale = $uri->detect();

        $this->assertEquals('en', $locale);

        $this->detector->setDriver($uri);

        $this->detector->detectAndApply();

        $this->assertEquals('en', $this->translator->getLocale());

        $prefix = $uri->routePrefix($this->translator->getLocale());

        $this->assertEquals('en', $prefix);
    }

    /**
     * Testing should alises the subdomain.
     */
    public function testShouldMatchesWithTheSubdomainAndAliases()
    {
        $this->translator->setLocale('fr');

        /** @var Request $request */
        $request = Request::create('http://example.com/en-us');

        $uri = new UriDetectorDriver($request, ['en', 'en-us' => 'en_US']);

        $locale = $uri->detect();

        $this->assertEquals('en_US', $locale);

        $this->detector->setDriver($uri);

        $this->detector->detectAndApply();

        $this->assertEquals('en_US', $this->translator->getLocale());

        $prefix = $uri->routePrefix($this->translator->getLocale());

        $this->assertEquals('en-us', $prefix);
    }
}
