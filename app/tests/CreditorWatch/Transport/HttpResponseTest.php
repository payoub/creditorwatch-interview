<?php

namespace tests\CreditorWatch\Transport;

require_once TEST_ROOT.DIRECTORY_SEPARATOR.'TestCase.php';

class HttpResponseTest extends \tests\TestCase {

	public function creationTest(){
		$responseCode = 200;
		$responseData = "{ 'response': 'string'}";
		$class = new \CreditorWatch\Transport\Response\HttpResponse($responseCode, $responseData);
		assert($class->getResponseCode() == $responseCode);
		assert($class->getResponseData() == $responseData);
	}

}
