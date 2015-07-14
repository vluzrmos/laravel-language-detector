<?php

namespace Vluzrmos\LanguageDetector;

/**
 * Class LanguageDetectorTest.
 */
class LanguageDetectorTest extends AbstractTestCase
{
    /**
     * @return void
     */
    public function testShouldNotChangeAppLanguage()
    {
        $detector = $this->createInstance('en');

        $this->setAppLocale('fr');

        $detected = $detector->detect(false);

        $locale = $this->getAppLocale();

        $this->assertNotEquals($locale, $detected);
    }

    /**
     * @return void
     */
    public function testShouldSeePtBRLanguage()
    {
        $detector = $this->createInstance('pt_BR');

        $this->setAppLocale('en');

        $detector->detect();

        $this->assertEquals('pt_BR', $this->getAppLocale());
    }

    /**
     * @return void
     */
    public function testShouldAliasPtToPtBrLanguage()
    {
        $detector = $this->createInstance('en', null, ['pt' => 'pt_BR', 'en']);

        $detected = $detector->detect(true);

        $this->assertNotEmpty($detected);

        $this->assertEquals('pt_BR', $this->getAppLocale());

        $this->assertEquals($detected, $this->getAppLocale());
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

        $this->assertEquals('some-value', $detector->keyOrValue(null, 'some-value'));
    }

    /**
     * @return void
     */
    public function testShouldReturnKey()
    {
        $detector = $this->createInstance();

        $this->assertEquals('some-key', $detector->keyOrValue('some-key', null));
    }

    /**
     * @return void
     */
    public function testShouldGetAppLanguages()
    {
        $detector = $this->createInstance('en', null, [
            'pt_BR',
            'en',
            'fr',
            'pt_BR' => 'pt_BR',
        ]);

        $this->assertEquals(['pt_BR', 'en', 'fr', 'pt_BR'], $detector->appLanguages());
    }

    /**
     * @return void
     */
    public function testShouldGetBrowserLanguages()
    {
        $detector = $this->createInstance('en', 'pt_BR,pt;q=0.8,en-US;q=0.6,en;q=0.4');

        $this->assertEquals(['pt_BR', 'pt', 'en_US', 'en'], $detector->browserLanguages());
    }

    /**
     * @return void
     */
    public function testShouldChooseBrowserLanguage()
    {
        $detector = $this->createInstance('fr', 'en-US,en;q=0.8,pt-BR;q=0.6,pt;q=0.4', ['pt_BR', 'en']);

        $this->assertEquals('en', $detector->detect(false));
    }

    /**
     * @return void
     */
    public function testShouldChooseBrowserLanguage2()
    {
        $detector = $this->createInstance('fr', 'en-US,en;q=0.8,pt-BR;q=0.6,pt;q=0.4', ['pt_BR', 'en_US']);

        $this->assertEquals('en_US', $detector->detect(false));
    }

    /**
     * @return void
     */
    public function testShouldChooseBrowserLanguage3()
    {
        $detector = $this->createInstance('fr', 'pt;q=0.8,en-US;q=0.6,en;q=0.4', ['pt' => 'pt_BR', 'en_US']);

        $this->assertEquals('pt_BR', $detector->detect(false));
    }

    /**
     * @return void
     */
    public function testShouldAliaseTheLocale()
    {
        $detector = $this->createInstance('pt_BR', 'en-US,en', [
            'pt' => 'pt_BR',
            'en_US' => 'en',
        ]);

        $this->setAppLocale('pt_BR');

        $locale = $detector->detect(false);

        $this->assertEquals('en', $locale);
        $this->assertEquals('en', $detector->getAliasedLocale('en_US'));
        $this->assertEquals('en', $detector->getAliasedLocale('en'));
    }

    /**
     * @return void
     */
    public function testShouldSetRealLocale()
    {
        $detector = $this->createInstance();

        $detector->setRealLocale('pt_BR');

        $this->assertEquals('pt_BR', $this->getAppLocale());
    }
}
