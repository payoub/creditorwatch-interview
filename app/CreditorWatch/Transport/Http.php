<?php

namespace CreditorWatch\Transport;

class Http implements TransportInterface {

	protected $request;
	protected $response;

	public static function makeRequest($requestData) {
		$request = new \CreditorWatch\Transport\Request\HttpRequest();
		$request->setRequestData($requestData);
		return $request;
	}

	public function sendRequest($request) {
		$this->request = $request;

		$ch = curl_init();

		curl_setopt_array($ch, $request->getRequestData());

		$responseData = curl_exec($ch);
		$responseCode = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
		$this->response = new \CreditorWatch\Transport\Response\HttpResponse($responseCode, $responseData);

		curl_close($ch);

		return $this;	
	}

	public function getResponse() {
		return $this->response;
	}

}