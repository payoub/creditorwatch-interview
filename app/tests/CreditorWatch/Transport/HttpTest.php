<?php

namespace tests\CreditorWatch\Transport;

require_once TEST_ROOT.DIRECTORY_SEPARATOR.'TestCase.php';

class HttpTest extends \tests\TestCase {

	public function basicRequestTest(){

		$request = new \CreditorWatch\Transport\Request\HttpRequest();
		$request->setUrlEndpoint("https://www.httpbin.org/get");

		$class = new \CreditorWatch\Transport\Http();
		$class->sendRequest($request);

		assert($class->getResponseCode() == 200);
		assert(is_string($class->getResponse()));
		assert(strlen($class->getResponse()) > 0);

	}

	public function statusCodeTest(){

		$request = new \CreditorWatch\Transport\Request\HttpRequest();
		$request->setUrlEndpoint("http://www.httpbin.org/status/400");

		$class = new \CreditorWatch\Transport\Http();
		$class->sendRequest($request);

		assert($class->getResponseCode() == 400);

	}

	public function queryStringTest(){

		$params = ["key" => "value"];

		$request = new \CreditorWatch\Transport\Request\HttpRequest();
		$request->setUrlEndpoint("http://www.httpbin.org/anything/{anything}")
				->setQueryParams($params);

		$class = new \CreditorWatch\Transport\Http();
		$class->sendRequest($request);

		$response = json_decode($class->getResponse());
		$responseParams = (array) $response->args;

		//Test for equality, order doesn't matter
		//See https://www.php.net/manual/en/language.operators.array.php
		assert($params == $responseParams); 
		assert($class->getResponseCode() == 200);

	}



}