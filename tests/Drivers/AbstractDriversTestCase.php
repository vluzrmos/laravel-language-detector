<?php

namespace Vluzrmos\LanguageDetector\Testing\Drivers;

use Orchestra\Testbench\TestCase;
use Symfony\Component\Translation\TranslatorInterface;
use Vluzrmos\LanguageDetector\LanguageDetector;

/**
 * Class AbstractDriversTestCase.
 */
abstract class AbstractDriversTestCase extends TestCase
{
    /**
     * @var LanguageDetector
     */
    protected $detector;

    /**
     * Symfony translator.
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

        $this->detector = new LanguageDetector($this->translator);
    }
}
