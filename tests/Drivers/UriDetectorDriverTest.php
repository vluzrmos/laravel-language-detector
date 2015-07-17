<?php

namespace Vluzrmos\LanguageDetector\Testing\Drivers;

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
        $uri = $this->createUriDetectorForRequest('http://example.com', ['en']);

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
        $uri = $this->createUriDetectorForRequest('http://example.com/en', ['en']);

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
        $uri = $this->createUriDetectorForRequest('http://example.com/en-us', ['en', 'en-us' => 'en_US']);

        $locale = $uri->detect();

        $this->assertEquals('en_US', $locale);

        $this->detector->setDriver($uri);

        $this->detector->detectAndApply();

        $this->assertEquals('en_US', $this->translator->getLocale());

        $prefix = $uri->routePrefix($this->translator->getLocale());

        $this->assertEquals('en-us', $prefix);
    }

    /**
     * Create an instance of UriDetectorDriver for a given Request Uri.
     *
     * @param string $requestUri Url of the request.
     * @param array  $languages  Languages available on the application.
     * @param string $method     Requested method.
     *
     * @return UriDetectorDriver
     */
    protected function createUriDetectorForRequest($requestUri, array $languages = [], $method = 'GET')
    {
        $request = $this->createRequest($requestUri, $method);

        return new UriDetectorDriver($request, $languages);
    }
}
