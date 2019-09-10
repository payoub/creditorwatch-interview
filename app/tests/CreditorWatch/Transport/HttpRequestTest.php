<?php

namespace tests\CreditorWatch\Transport;

require_once TEST_ROOT.DIRECTORY_SEPARATOR.'TestCase.php';

class HttpRequestTest extends \tests\TestCase {

	public function setEndpointTest(){
		$url = "http://test.url.com";
		$class = new \CreditorWatch\Transport\HttpRequest();
		$class->setUrlEndpoint($url);
		$options = $class->getRequestAsCurlOptions();
		assert($options[CURLOPT_URL] === $url);
	}

	public function setMethodGetTest(){
		$class = new \CreditorWatch\Transport\HttpRequest();
		$class->setMethodGet();
		$options = $class->getRequestAsCurlOptions();
		assert(!isset($options[CURLOPT_POST]));
		assert(!isset($options[CURLOPT_POSTFIELDS]));
	}

	public function setMethodPostTest(){
		$class = new \CreditorWatch\Transport\HttpRequest();
		$class->setMethodPost();
		$options = $class->getRequestAsCurlOptions();
		assert(isset($options[CURLOPT_POST]));
		assert(isset($options[CURLOPT_POSTFIELDS]));
	}

	public function setPostBodyTest(){
		$postFields = ["key" => "value"];
		$class = new \CreditorWatch\Transport\HttpRequest();
		$class->setMethodPost();
		$class->setPostBody($postFields);
		$options = $class->getRequestAsCurlOptions();
		assert(isset($options[CURLOPT_POST]));
		assert(isset($options[CURLOPT_POSTFIELDS]));
		assert($options[CURLOPT_POSTFIELDS] === $postFields);
	}

	public function setQueryParamTest(){
		$url = "http://test.url.com";
		$queryArray = ["key" => "value"];

		$class = new \CreditorWatch\Transport\HttpRequest();
		$class->setUrlEndpoint($url);
		$class->setQueryParams($queryArray);

		$queryString = http_build_query($queryArray);
		$expected = sprintf("%s?%s",$url,$queryString);
		$options = $class->getRequestAsCurlOptions();

		assert($options[CURLOPT_URL] === $expected);
	}

	
}