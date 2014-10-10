<?php

namespace build;

include "vendor/autoload.php";

use Composer\Script\Event;

class SuiteBuilder
{
	public static function sym_link_test_folders(Event $event)
	{
		// we only want to create the symlinks if we are in dev mode (e.g. run with --dev flag)
		if (!$event->isDevMode())
		{
			echo "Not in dev mode. exiting sym_link_test_folders";
			return;
		}

		$lyons_dir = realpath(__DIR__ ."/../app/code/local/Lyonscg/");

		echo $lyons_dir ." = lyons_dir\n";

		$sub_dirs = scandir($lyons_dir);

		var_dump($sub_dirs);

		// read through the directories in app/code/local/lyonscg and find "Test" dirs
		foreach ($sub_dirs as $module_dir)
		{
			if ($module_dir == "." || $module_dir == "..")
				continue;

			$module_name = substr( $module_dir, strrpos($module_dir, DIRECTORY_SEPARATOR) );			
			$dirs = scandir($lyons_dir . DIRECTORY_SEPARATOR . $module_dir);

			echo $module_dir ."\n";
			echo $module_name ."\n";;
			var_dump($dirs);

			if (in_array("Tests", $dirs))
			{
				echo $module_name ."/Tests found. Creating symlink\n";

				// create a symlink between /Tests/MyModule -> /app/code/local/Lyonscg/MyModule/Tests 
				@symlink( $lyons_dir . DIRECTORY_SEPARATOR . $module_dir . DIRECTORY_SEPARATOR . "Tests", __DIR__ ."/../Tests/". $module_name);	
			}
			else
			{
				echo $module_name ."/Tests NOT found.\n";
			}
		}
	}

	public static function update_phpunit_xml(Event $event)
	{
		// we only want to create the symlinks if we are in dev mode (e.g. run with --dev flag)
		if (!$event->isDevMode())
		{
			echo "Not in dev mode. exiting update_phpunit_xml";
			return;
		}

		if (!file_exists(__DIR__ ."/phpunit.xml"))
		{
			echo "missing build/phpunit.xml file.\nExiting phpunit.xml build process\n";
			return;
		}

		$xml = simplexml_load_file(__DIR__ .'/phpunit.xml');

		$dirs = scandir(__DIR__ .'/../Tests');

		$all_testsuite = $xml->xpath("//testsuite[@name='All']");
		var_dump($all_testsuite);

		foreach ($dirs as $dir)
		{
			if ($dir == "." || $dir == "..")
				continue;
			
			$all_testsuite[0]->addChild("directory", "../Tests/". $dir);
		}

		$xml->asXml(__DIR__ ."/phpunit.xml");
	}
}
