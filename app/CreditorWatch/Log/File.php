<?php

namespace CreditorWatch\Log;

/**
 * Writes log messages to a file.
 */
class File {

	protected $logPath;
	protected $logName = 'app.log';
	protected $logFormat = "[%s] %s: %s\n";
	protected $fileResource;

	/**
	 * Sets which path to log messages to.
	 * Default is /tmp/app.log if no path given.
	 * Throws exception if path is not writeable.
	 * 
	 * @param string $path
	 * @throws \Exception
	 */
	public function __construct($path = '/tmp') {
		@touch($path);
		if(is_writeable($path) && is_dir($path)){
			$this->logPath = $path . DIRECTORY_SEPARATOR . $this->logName;
		}elseif(is_writable($path)){
			$this->logPath = $path;
		}else {
			throw new \Exception('Log path not writeable');
		}
	}

	public function __destruct() {
		if(is_resource($this->fileResource)){
			fclose($this->fileResource);
		}
	}

	public function getLogPath(){
		return $this->logPath;
	}

	public function debug($msg) {
		$this->write('DEBUG', $msg);
	} 

	public function info($msg) {
		$this->write('INFO', $msg);
	} 

	public function error($msg) {
		$this->write('ERROR', $msg);
	}

	protected function write($level, $msg){

		if(is_null($this->fileResource)){
			$this->fileResource = fopen($this->getLogPath(), 'w');
		}

		$msg = sprintf($this->logFormat, date('r'), $level, $msg);
		
		fwrite($this->fileResource, $msg);
	}
	
}