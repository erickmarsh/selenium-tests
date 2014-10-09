<?php

include (__DIR__ ."/../vendor/autoload.php");

class BrowserConfig
{
	static $default_browser = "firefox";

	static public function get_capabilities()
	{

		phpinfo(INFO_ENVIRONMENT);
		phpinfo(INFO_VARIABLES);

		// check if we are running this locally
		// if so, go with a minimal setup
		if (stripos(self::get_host(), "localhost") !== FALSE)
		{
			return array(\WebDriverCapabilityType::BROWSER_NAME => BrowserConfig::get_browser());
		}
		else
		{
			return array(\WebDriverCapabilityType::BROWSER_NAME => BrowserConfig::get_browser(),
                  		 \WebDriverCapabilityType::VERSION      => BrowserConfig::get_version(),
                  		 \WebDriverCapabilityType::PLATFORM     => BrowserConfig::get_platform());
		}
	}

	static public function get_platform()
	{
		return self::get_var("SELENIUM_PLATFORM", "WINDOWS");
	}

	static public function get_browser()
	{
		return self::get_var("SELENIUM_BROWSER", self::$default_browser);
	}

	static public function get_version()
	{
		return self::get_var("SELENIUM_VERSION", "32");
	}

	static public function get_host()
	{
		return self::get_var("SAUCE_ONDEMAND_HOST", 
			                 "http://localhost:4444/wd/hub");
	}

	static public function get_timeout()
	{
		return 30000;
	}

	static private function get_var($env_var, $default)
	{
		return ($_SERVER[$env_var] !== false) ? $_SERVER[$env_var] : $default;
	}

}
