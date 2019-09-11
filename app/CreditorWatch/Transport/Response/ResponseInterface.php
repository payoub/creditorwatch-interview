<?php

namespace CreditorWatch\Transport\Response;

interface ResponseInterface {

	public function __construct($responseCode, $responseData);
	public function getResponseData();
	public function getResponseCode();
	public function isSuccess();

}
