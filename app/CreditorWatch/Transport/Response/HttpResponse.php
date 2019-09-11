<?php

namespace CreditorWatch\Transport\Response;

class HttpResponse implements ResponseInterface {

	protected $responseData;
	protected $responseCode;
	
	public function __construct($responseCode, $responseData) {
		$this->responseData = $responseData;
		$this->responseCode = $responseCode;
	}

	public function getResponseData() {
		return $this->responseData;
	}

	public function getResponseCode(){
		return $this->responseCode;
	}

	public function isSuccess(){
		return $this->getResponseCode() == 200;
	}
}
