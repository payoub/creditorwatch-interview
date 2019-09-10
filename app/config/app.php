<?php

$config = [];

$config['log'] = [
	'logType' => 'CreditorWatch\Log\File'
	, 'logPath' => APP_ROOT.DIRECTORY_SEPARATOR.'logs' 
];

$config['searchEngines'] = [
	"google" => [
		"searchEndpoint" => "https://www.googleapis.com/customsearch/v1"
		, "token" => ""
		, "engineId" => ""
	]
];


return $config;