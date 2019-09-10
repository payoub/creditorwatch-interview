<?php

namespace CreditorWatch\Transport;

class Http implements TransportInterface {

	protected $request;
	protected $response;
	protected $responseCode;

	public function sendRequest($request) {
		$this->request = $request;

		$ch = curl_init();

		curl_setopt_array($ch, $request->getRequestData());

		$this->response = curl_exec($ch);
		$this->responseCode = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);

		curl_close($ch);

		return $this;	
	}

	public function getResponse() {
		return $this->response;
	}

	public function getResponseCode(){
		return $this->responseCode;

	}
	
}