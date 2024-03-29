<?php

namespace tests\CreditorWatch\Transport;

require_once TEST_ROOT.DIRECTORY_SEPARATOR.'TestCase.php';

class HttpTest extends \tests\TestCase {

	public function basicRequestTest(){

		$request = new \CreditorWatch\Transport\Request\HttpRequest();
		$request->setUrlEndpoint("https://www.httpbin.org/get");

		$class = new \CreditorWatch\Transport\Http();
		$class->sendRequest($request);

		assert($class->getResponse()->getResponseCode() == 200);
		assert(is_a($class->getResponse(),"\CreditorWatch\Transport\Response\HttpResponse"));
		assert(strlen($class->getResponse()->getResponseData()) > 0);

	}

	public function statusCodeTest(){

		$request = new \CreditorWatch\Transport\Request\HttpRequest();
		$request->setUrlEndpoint("http://www.httpbin.org/status/400");

		$class = new \CreditorWatch\Transport\Http();
		$class->sendRequest($request);

		assert($class->getResponse()->getResponseCode() == 400);

	}

	public function queryStringTest(){

		$params = ["key" => "value"];

		$request = new \CreditorWatch\Transport\Request\HttpRequest();
		$request->setUrlEndpoint("http://www.httpbin.org/anything/{anything}")
				->setQueryParams($params);

		$class = new \CreditorWatch\Transport\Http();
		$class->sendRequest($request);

		$response = json_decode($class->getResponse()->getResponseData());
		$responseParams = (array) $response->args;

		//Test for equality, order doesn't matter
		//See https://www.php.net/manual/en/language.operators.array.php
		assert($params == $responseParams); 
		assert($class->getResponse()->getResponseCode() == 200);

	}

	public function makeRequestTest(){

		$requestData = [
			'endpoint' => 'http://test.endpoint.com/v1',
			'method' => 'GET',
			'queryParams' => [
				'q' => 'creditor watch' 
				, 'start' => 1 
				, 'key' => 'TEST' 
				, 'cx' => 'TEST' 
			]  
		];

		$class = new \CreditorWatch\Transport\Http();
		$request = $class::makeRequest($requestData);

		$queryString = http_build_query($requestData['queryParams']);
		$expected = sprintf("%s?%s",$requestData['endpoint'],$queryString);
		$options = $request->getRequestData();

		assert($options[CURLOPT_URL] === $expected);
	}
}