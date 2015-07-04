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
    public function testShouldNotChangeAppLanguage()
    {
        $detector = $this->createInstance('en');

        $detected = $detector->detect(false);

        $locale = $this->getAppLocale();

        $this->assertNotEquals($locale, $detected);
    }

    /**
     * @return void
     */
    public function testShouldSeePtBRLanguage()
    {
        $detector = $this->createInstance('pt-BR');

        $this->setAppLocale('en');

        $detector->detect();

        $this->assertEquals('pt-BR', $this->getAppLocale());
    }

    /**
     * @return void
     */
    public function testShouldAliasPtToPtBrLanguage()
    {
        $detector = $this->createInstance('en', null, ['pt' => 'pt-BR', 'en']);

        $detected = $detector->detect(true);

        $this->assertNotEmpty($detected);

        $this->assertEquals('pt-BR', $detected);
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

    /**
     * @return void
     */
    public function testShouldReturnValue()
    {
        $detector = $this->createInstance();

        $this->assertEquals("some-value", $detector->keyOrValue(null, "some-value"));
    }

    /**
     * @return void
     */
    public function testShouldReturnKey()
    {
        $detector = $this->createInstance();

        $this->assertEquals("some-key", $detector->keyOrValue("some-key", null));
    }

    /**
     * @return void
     */
    public function testShouldGetAppLanguages()
    {
        $detector = $this->createInstance('en', null, [
            'pt-BR',
            'en',
            'fr',
            'pt_BR' => 'pt-BR',
        ]);

        $this->assertEquals(['pt-BR', 'en', 'fr', 'pt_BR'], $detector->appLanguages());
    }

    /**
     * @return void
     */
    public function testShouldGetBrowserLanguages()
    {
        $detector = $this->createInstance('en', 'pt-BR,pt;q=0.8,en-US;q=0.6,en;q=0.4');

        $this->assertEquals('pt-BR,pt;q=0.8,en-US;q=0.6,en;q=0.4', $detector->browserLanguages());
    }

    /**
     * @return void
     */
    public function testShouldAliaseTheLocale()
    {
        $detector = $this->createInstance('pt-BR', 'en', [
            'pt' => 'pt-BR',
            'en-US' => 'en',
        ]);

        $this->setAppLocale('pt-BR');

        $locale = $detector->detect(false);

        $this->assertEquals('en', $locale);
        $this->assertEquals('en', $detector->getAliasedLocale('en-US'));
        $this->assertEquals('en', $detector->getAliasedLocale('en'));
    }

    /**
     * @return void
     */
    public function testShouldSetRealLocale()
    {
        $detector = $this->createInstance();

        $detector->setRealLocale('pt-BR');

        $this->assertEquals('pt-BR', $this->getAppLocale());
    }
}
