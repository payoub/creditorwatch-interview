<?php

namespace CreditorWatch\Transport;

interface TransportInterface {

	public function sendRequest($request);
	public function getResponse();
	public function getResponseCode();

} 