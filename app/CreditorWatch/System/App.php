<?php
namespace CreditorWatch\System;

final class App {

	private static $instance;
	private $log;
	private $config;

	private function __construct($config){
		if(!isset($config['log'])){
			throw new \Exception('Application logging not configured');
		}

		$this->log = new $config['log']['logType']($config['log']['logPath']); 
		$this->config = $config;

	}

	public static function getInstance(){
		if(is_null(self::$instance)){
			$config = include APP_ROOT.DIRECTORY_SEPARATOR."config".DIRECTORY_SEPARATOR."app.php";
			self::$instance = new self($config);
		}
		return self::$instance;
	}

	public function log(){
		return $this->log;
	}

	public function config($key = null){
		if(is_null($key)){
			return $this->config;
		}
		return $this->config[$key];
	}



}
