<?php

namespace tests;

require_once "../bootstrap.php"; 

class TestCase {

	private $totalTests = 0;
	private $successTests = 0;

	public function runTests() {

		echo "Running Tests for ".get_called_class().PHP_EOL;
		$methods = array_filter(get_class_methods($this), function($e){
			return preg_match('/^.*Test$/', $e); 
		});

		foreach($methods as $method){
			$this->totalTests += 1;
			try {
				$this->$method();
				echo ' * '.$method.': OK'.PHP_EOL;
				$this->successTests += 1;
			} catch (\AssertionError $e){
				echo ' * '.$method.': FAIL'.PHP_EOL;
				echo "-> Assert failed in {$e->getFile()} on Line {$e->getLine()}".PHP_EOL;
				echo "-> ".$e->getMessage().PHP_EOL; 
			}
		}
	}	

	public function getTotalTests(){
		return $this->totalTests;
	}
	
	public function getSuccessTests(){
		return $this->successTests;
	}
}