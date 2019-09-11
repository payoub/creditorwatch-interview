<?php

namespace CreditorWatch\Log;

abstract class AbstractLog {

	abstract public function getLogPath();
	abstract public function debug($msg);
	abstract public function info($msg);
	abstract public function error($msg);
}
