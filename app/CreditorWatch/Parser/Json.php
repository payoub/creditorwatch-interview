<?php

namespace CreditorWatch\Parser;

class Json implements ParserInterface {

	public function parse($response) {
		return json_decode($response);
	}
	
}