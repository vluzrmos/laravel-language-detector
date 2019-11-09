<?php

namespace Vluzrmos\LanguageDetector\Testing\Drivers;

use Illuminate\Http\Request;
use Orchestra\Testbench\TestCase;
use Symfony\Component\Translation\TranslatorInterface;
use Vluzrmos\LanguageDetector\LanguageDetector;

/**
 * Class AbstractDriversTestCase.
 */
abstract class AbstractDriversTestCase extends TestCase
{
    /**
     * Language Detector instance.
     * @var LanguageDetector
     */
    protected $detector;

    /**
     * Symfony translator instance.
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * Set up the tests.
     */
    public function setUp() : void
    {
        parent::setUp();

        $this->translator = $this->app['translator'];

        $this->detector = new LanguageDetector($this->translator);

        $this->translator->setLocale('fr');
    }

    /**
     * Create a new request instance.
     *
     * @param string $uri
     * @param string $method
     * @param string  $acceptedLanguages
     *
     * @return \Illuminate\Http\Request
     */
    public function createRequest($uri = 'http://localhost:8000', $method = 'GET', $acceptedLanguages = 'en-us,en;q=0.8')
    {
        $request = Request::create($uri, $method);

        $request->headers->set('accept_language', $acceptedLanguages);

        return $request;
    }
}
