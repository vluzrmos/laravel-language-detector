<?php

namespace Vluzrmos\LanguageDetector\Testing\Drivers;

use Vluzrmos\LanguageDetector\Drivers\SubdomainDetectorDriver;

/**
 * Class SubdomainDetectorDriverTest.
 */
class SubdomainDetectorDriverTest extends AbstractDriversTestCase
{
    /**
     * Testing when should not change locale: hostname without subdomain.
     */
    public function testShouldNotChangeTheLocale()
    {
        $subdomain = $this->createSubdomainDetectorForRequest('http://example.com', ['en']);

        $locale = $subdomain->detect();

        $this->assertEmpty($locale);

        $this->detector->setDriver($subdomain);

        $this->detector->detectAndApply();

        $this->assertEquals('fr', $this->translator->getLocale());
    }

    /**
     * Testing should change the locale matching if available with subdomain.
     */
    public function testShouldMatchesWithTheSubdomain()
    {
        $subdomain = $this->createSubdomainDetectorForRequest('http://en.example.com', ['en']);

        $locale = $subdomain->detect();

        $this->assertEquals('en', $locale);

        $this->detector->setDriver($subdomain);

        $this->detector->detectAndApply();

        $this->assertEquals('en', $this->translator->getLocale());
    }

    /**
     * Testing should alises the subdomain.
     */
    public function testShouldMatchesWithTheSubdomainAndAliases()
    {
        $subdomain = $this->createSubdomainDetectorForRequest('http://en-us.example.com', ['en', 'en-us' => 'en_US']);

        $locale = $subdomain->detect();

        $this->assertEquals('en_US', $locale);

        $this->detector->setDriver($subdomain);

        $this->detector->detectAndApply();

        $this->assertEquals('en_US', $this->translator->getLocale());
    }

    /**
     * Create an instance of SubdomainDetectorDriver for a given Request Uri.
     *
     * @param string $requestUri Url of the Request.
     * @param array  $languages  Languages available on the application.
     * @param string $method     Requested Method.
     *
     * @return SubdomainDetectorDriver
     */
    protected function createSubdomainDetectorForRequest($requestUri, array $languages = [], $method = 'GET')
    {
        $request = $this->createRequest($requestUri, $method);

        return new SubdomainDetectorDriver($request, $languages);
    }
}
