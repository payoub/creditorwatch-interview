<?php

namespace CreditorWatch\Transport;

interface TransportInterface {

	public static function makeRequest($requestData);
	public function sendRequest($request);
	public function getResponse();

} 