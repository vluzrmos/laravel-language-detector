<?php

namespace Vluzrmos\LanguageDetector;

/**
 * Class LanguageDetectorTest
 * @package Vluzrmos\LanguageDetector
 */
class LanguageDetectorTest extends AbstractTestCase
{

    /**
     * @return void
     */
    public function testShouldDetectEnLanguage()
    {
        $detector = $this->createInstance();

        $this->setLocale('en');

        $this->assertEquals('pt-BR', $detector->detect(false), "The language doesn't matches with 'en'.");
    }

    /**
     * @return void
     */
    public function testShouldSeePtBRLanguage()
    {
        $detector = $this->createInstance();

        $detector->detect();

        $this->assertEquals('pt-BR', $this->getLocale(), "The application language doesn't matches with 'en'.");
    }

    /**
     * @return void
     */
    public function testShouldAliasePtToPtBrLanguage()
    {
        $detector = $this->createInstance('pt', ['pt' => 'pt-BR', 'pt-BR']);

        $detector->detect();

        $this->assertEquals('pt-BR', $this->getLocale(), "The application language doesn't matches with 'en'.");
    }

    /**
     * @return void
     */
    public function testShouldNotDetectTheLanguageAndSeeDefault()
    {
        /* UNdetectable LOcale */
        $detector = $this->createInstance('un-LO');

        $locale = $this->getLocale();

        $detected = $detector->detect(true);

        $this->assertEmpty($detected);

        $this->assertEquals($locale, $this->getLocale());
    }
}
