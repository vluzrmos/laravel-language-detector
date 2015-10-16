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
    public function setUp()
    {
        parent::setUp();

        $this->translator = $this->app['translator'];

        $this->detector = new LanguageDetector();

        $this->app->setLocale('fr');
    }

    /**
     * Create a new request instance.
     *
     * @param string $uri
     * @param string $method
     * @return Request
     */
    public function createRequest($uri = 'http://localhost:8000', $method = 'GET')
    {
        return Request::create($uri, $method);
    }
}
