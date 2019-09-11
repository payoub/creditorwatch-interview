<?php

namespace tests\CreditorWatch\System;

require_once TEST_ROOT.DIRECTORY_SEPARATOR.'TestCase.php';

class AppTest extends \tests\TestCase {

	public function getInstanceTest(){
		$app = \CreditorWatch\System\App::getInstance();
		$app2 = \CreditorWatch\System\App::getInstance();
		assert(is_a($app, "\CreditorWatch\System\App"));
		assert($app === $app2);
	}

	public function getAllConfigTest(){
		$app = \CreditorWatch\System\App::getInstance();
		$config = $app->getConfig();
		assert(is_array($config));
		assert(!empty($config));
	}

	public function getLogConfigTest(){
		$app = \CreditorWatch\System\App::getInstance();
		$config = $app->getConfig('log');
		assert(is_array($config));
		assert(!empty($config));
		assert(isset($config['logType']));
	}

	public function getAppLoggerTest(){
		$app = \CreditorWatch\System\App::getInstance();
		$logType = $app->getConfig('log')['logType'];
		assert(is_a($app->getLog(), $logType));
		
	}

}
