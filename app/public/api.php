<?php

require "../bootstrap.php";

use CreditorWatch\System\App;

App::getInstance()->getLog()->debug("API Got request: ".var_export($_REQUEST, true));

$keywords = $_GET['keywords'];
$targetUrl = $_GET['target'];

$transportStrategy = new \CreditorWatch\Transport\Http();
$parserStrategy = new \CreditorWatch\Parser\Json();
$google = new \CreditorWatch\SearchEngine\Google($transportStrategy, $parserStrategy);

$google->query($keywords);
$count = 9; //Get 100 results
$results = $google->getResults();

while($google->hasMoreResults() && $count > 0){
	$count--;
	$google->getNextPage();
	$results = array_merge($results, $google->getResults());
}

App::getInstance()->getLog()->debug("Total results = ".count($results));
App::getInstance()->getLog()->debug(var_export($results, true));

$templateFound = "<p>%s was found at the following positions within the top 100 Google results:</p><ul>%s</ul>";
$templateNotFound = "<p>%s was <strong>not found</strong> while searching the top 100 Google results.</p>";

if($results == false){
	http_response_code(502);
	$msg = "Unable to retrieve results from Google.".PHP_EOL.PHP_EOL;
	if(isset($google->getResponse()->error)){
		$msg .= print_r($google->getResponse()->error, true);
	}
	$response = ["success" => false, "html" => $msg];
}else{
	$html = sprintf($templateNotFound, $targetUrl);
	$response = (object)["success" => true, "html" => $html];
}



header("Content-Type:application/json");
echo json_encode($response);	


