<?php

namespace tests\CreditorWatch\Log;

require_once TEST_ROOT.DIRECTORY_SEPARATOR.'TestCase.php';

class FileTest extends \tests\TestCase {

	public function checkPathIsWriteableTest(){
		$nonExistantPath = "/this/path/doesnt/exist";
		try{
			$class = new \CreditorWatch\Log\File($nonExistantPath);
			assert(false);
		} catch (\Exception $e) {
			assert(true);
		}
	}

	public function checkDefaultPathTest(){
		$class = new \CreditorWatch\Log\File();
		assert(is_string($class->getLogPath()));
		assert($class->getLogPath() === "/tmp/app.log");
	}
	
	
	public function checkDebugLogTest(){
		$logpath = "/tmp/app.log-".time();
		$class = new \CreditorWatch\Log\File($logpath);
		$class->debug("checkDebugLogTest");

		$contents = file_get_contents($logpath);
		assert(is_file($logpath));
		assert(strpos($contents, "checkDebugLogTest") !== false);
		assert(strpos($contents, "DEBUG") !== false);
	}

	public function checkInfoLogTest(){
		$logpath = "/tmp/app.log-".time();
		$class = new \CreditorWatch\Log\File($logpath);
		$class->info("checkInfoLogTest");

		$contents = file_get_contents($logpath);
		assert(is_file($logpath));
		assert(strpos($contents, "checkInfoLogTest") !== false);
		assert(strpos($contents, "INFO") !== false);
	}

	public function checkErrorLogTest(){
		$logpath = "/tmp/app.log-".time();
		$class = new \CreditorWatch\Log\File($logpath);
		$class->error("checkErrorLogTest");

		$contents = file_get_contents($logpath);
		assert(is_file($logpath));
		assert(strpos($contents, "checkErrorLogTest") !== false);
		assert(strpos($contents, "ERROR") !== false);
	}
	
} 