<?php

namespace CreditorWatch\Parser;

use CreditorWatch\System\App;

class Json implements ParserInterface {

	public function parse($response) {
		App::getInstance()->getLog()->debug("Parsing response data");
		return json_decode($response);
	}
	
}