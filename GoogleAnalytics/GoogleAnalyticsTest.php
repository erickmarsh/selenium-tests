<?php


class GoogleAnalyticsTest extends PHPUnit_Framework_TestCase
{

    static $D;              // Static Driver
    protected $webDriver;

    public static function setUpBeforeClass()
    {
        $capabilities = array(\WebDriverCapabilityType::BROWSER_NAME => 'firefox');
        self::$D = RemoteWebDriver::create('http://localhost:4444/wd/hub', $capabilities);
        self::$D->manage()->timeouts()->implicitlyWait(30); 
    }

    public static function tearDownAfterClass()
    {
        self::$D->close();
    }


    public function testStaticDriver()
    {
        self::$D->get("http://www.github.com");
        // checking that page title contains word 'GitHub'
        $this->assertContains('GitHub', self::$D->getTitle());
    }


    public function testPageTitle()
    {

        self::$D->get("http://www.github.com");
        // checking that page title contains word 'GitHub'
        $this->assertContains('GitHub', self::$D->getTitle());
    }


    public function testGoogleAnalyticsCode()
    {
        $ga_code = 'UA-2853604-2';

        self::$D->get("http://www.rockler.com");
        $source = self::$D->getPageSource();

        // Checks if the GA code is actually in the HTML source
        $this->assertTrue( stripos($source, $ga_code) !== FALSE );
    }

}
