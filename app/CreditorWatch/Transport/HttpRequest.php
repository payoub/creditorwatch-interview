<?php

namespace CreditorWatch\Transport;

class HttpRequest {

	protected $endpoint;
	protected $method = 'GET';
	protected $queryParams = array();
	protected $postFields = array();


	public function setUrlEndpoint($url){
		$this->endpoint = $url;
		return $this;
	}

	public function setMethodGet(){
		return $this->setMethod('GET');
	}

	public function setMethodPost(){
		return $this->setMethod('POST');
	}

	public function setQueryParams(array $params){
		$this->queryParams = $params;
		return $this;
	}

	public function setPostBody(array $postFields){
		$this->setMethodPost();
		$this->postFields = $postFields;
		return $this;
	}

	protected function setMethod(string $method){
		$this->method = $method;
		return $this;
	}

	public function getRequestAsCurlOptions(){
		$queryString = http_build_query($this->queryParams);

		$endpoint = (strlen($queryString) > 0) ? $this->endpoint."?".$queryString : $this->endpoint; 

		$options = [
			CURLOPT_URL => $endpoint,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_FOLLOWLOCATION => true,
		];

		if($this->method === "POST"){
			$options[CURLOPT_POST] = true;
			$options[CURLOPT_POSTFIELDS] = $this->postFields;
		}

		return $options;
	}

}
