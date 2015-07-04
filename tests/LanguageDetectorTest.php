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
        $detector = $this->createInstance('en');

        $this->setAppLocale('en');

        $this->assertEquals('pt-BR', $detector->detect(false));
    }

    /**
     * @return void
     */
    public function testShouldSeePtBRLanguage()
    {
        $detector = $this->createInstance('pt-BR');

        $detector->detect();

        $this->assertEquals('pt-BR', $this->getAppLocale());
    }

    /**
     * @return void
     */
    public function testShouldAliasPtToPtBrLanguage()
    {
        $detector = $this->createInstance('pt-BR', 'pt', ['pt' => 'pt-BR', 'pt-BR']);

        $detector->detect();

        $this->assertEquals('pt-BR', $this->getAppLocale());
    }

    /**
     * @return void
     */
    public function testShouldNotDetectTheLanguageAndSeeDefault()
    {
        /* UNdetectable LOcale */
        $detector = $this->createInstance('un-LO', 'un-LO');

        $locale = $this->getAppLocale();

        $detected = $detector->detect(true);

        $this->assertEmpty($detected);

        $this->assertEquals($locale, $this->getAppLocale());
    }
}
