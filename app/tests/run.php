<?php
/**
 * Triggers the execution of the test suite.
 * 
 * Recursively looks for all files which end in Test.php and includes them.
 * These classes are expected to be under the tests\CreditorWatch namespace and
 * extend from the base tests\TestCase class.
 */

//Used so test classes know where the base class is
define('TEST_ROOT', __DIR__);

//Ensure assertions throw exceptions and not warnings
ini_set('assert.exception', 1); 

//Create header
echo str_repeat("=", 40).PHP_EOL;
echo "Running all tests...".PHP_EOL;
echo "Current directory: ".__DIR__.PHP_EOL;
echo str_repeat("=", 40).PHP_EOL;

//Load all test classes
$dir = new RecursiveDirectoryIterator(__DIR__);
$ite = new RecursiveIteratorIterator($dir);
$files = new RegexIterator($ite, '/.*Test.php$/', RegexIterator::GET_MATCH);

foreach($files as $fileArray) {
	$testCasePath = array_shift($fileArray);
	include $testCasePath;
}

/*
 * get_declared_classes returns everything in the current scope
 * Filter out only the ones in the tests namespace
 */
$testClasses = array_filter(get_declared_classes(), function($e){
	return strpos($e, 'tests\CreditorWatch') !== false; 
});

$totalTests = 0;
$successTests = 0;

//Run the tests
$startTime = microtime(true);

foreach($testClasses as $class){
	/* @var $testCase \tests\TestCase */
	$testCase = new $class();
	$testCase->runTests();
	// Gather Stats
	$totalTests += $testCase->getTotalTests();
	$successTests += $testCase->getSuccessTests();
}

$endTime = microtime(true);

//Create footer and execution summary
$failedTests = $totalTests - $successTests;
$successRate = number_format(100*($successTests / $totalTests), 2);
$duration = number_format($endTime - $startTime, 2);

echo str_repeat("=", 40).PHP_EOL;
echo "Completed in $duration seconds".PHP_EOL;
echo "ok\t= $successTests".PHP_EOL;
echo "failed\t= $failedTests".PHP_EOL;
echo "total\t= $totalTests".PHP_EOL;
echo "rate\t= {$successRate}%".PHP_EOL;
echo str_repeat("=", 40).PHP_EOL;
