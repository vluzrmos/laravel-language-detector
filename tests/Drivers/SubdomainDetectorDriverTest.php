<?php

namespace Vluzrmos\LanguageDetector\Testing\Drivers;

use Illuminate\Http\Request;
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
        $this->translator->setLocale('fr');

        /** @var Request $request */
        $request = Request::create('http://example.com');

        $subdomain = new SubdomainDetectorDriver($request, ['en']);

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
        $this->translator->setLocale('fr');

        /** @var Request $request */
        $request = Request::create('http://en.example.com');

        $subdomain = new SubdomainDetectorDriver($request, ['en']);

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
        $this->translator->setLocale('fr');

        /** @var Request $request */
        $request = Request::create('http://en-us.example.com');

        $subdomain = new SubdomainDetectorDriver($request, ['en', 'en-us' => 'en_US']);

        $locale = $subdomain->detect();

        $this->assertEquals('en_US', $locale);

        $this->detector->setDriver($subdomain);

        $this->detector->detectAndApply();

        $this->assertEquals('en_US', $this->translator->getLocale());
    }
}
