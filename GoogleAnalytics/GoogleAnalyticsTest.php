<?php

//include ("../build/sausce-bootstrap.php");

class GoogleAnalyticsTest extends PHPUnit_Framework_TestCase
{

    static $WD;      // WebDriver

    public static function setUpBeforeClass()
    {

        $capabilities = BrowserConfig::get_capabilities();

        self::$WD = RemoteWebDriver::create(BrowserConfig::get_host(), $capabilities);
        self::$WD->manage()->timeouts()->implicitlyWait(30); 
    }

    public static function tearDownAfterClass()
    {
        self::$WD->close();
    }


    public function testStaticDriver()
    {
        self::$WD->get("http://www.github.com");
        // checking that page title contains word 'GitHub'
        $this->assertContains('GitHub', self::$WD->getTitle());
    }


    public function testPageTitle()
    {

        self::$WD->get("http://www.github.com");
        // checking that page title contains word 'GitHub'
        $this->assertContains('GitHub', self::$WD->getTitle());
    }


    public function testGoogleAnalyticsCode()
    {
        $ga_code = 'UA-2853604-2';

        self::$WD->get("http://www.rockler.com");
        $source = self::$WD->getPageSource();

        // Checks if the GA code is actually in the HTML source
        $this->assertTrue( stripos($source, $ga_code) !== FALSE );
    }

}
