<?php

require "../bootstrap.php";

use CreditorWatch\System\App;

/*
 * TODO: Create proper api interface instead of chucking everything in one file
 */

App::getInstance()->getLog()->debug("API Got request: ".var_export($_REQUEST, true));

/*
 *  Get params from query string. Production should validate 
 *  and strip these variables of any unsafe characters.
 */

$keywords = $_GET['keywords'];
$targetUrl = $_GET['target'];

/*
 * Manufacture our search engine. This could very well be based on a param
 * from above and passed into a factory as all search engines have a common
 * interface.
 */
$transportStrategy = new \CreditorWatch\Transport\Http();
$parserStrategy = new \CreditorWatch\Parser\Json();
$google = new \CreditorWatch\SearchEngine\Google($transportStrategy, $parserStrategy);

/*
 * Create our processor to interpret the data. We could potentially also get
 * this provided to us from the search engine as interpretation would be specific
 * to the data provided.
 * 
 * The processor could use the chain of responsibility pattern so that we can 
 * stack operations for each set of results returned from Google. The CEO will 
 * undoubtedly get creative and want to extract more information after (s)he uses 
 * the application for some time. 
 */
$url = parse_url($targetUrl);
$rankProcessor = new CreditorWatch\Processor\Google\Rank($google, $url['host']);

/*
 * The magic starts here.
 * 
 * Query the keywords provided and use the processor to search for the targetUrl
 * and store the rankings. 
 * 
 * Google search returns 10 results at a time. The first query must be made to 
 * check if there are any results (of course there are, it's Google).
 * 
 * We can call getNextPage to get the next set of results and do this 9 times for
 * a total of 100 records as requested by the CEO. This should really be part of
 * this api and based on a queryString param set above.
 * 
 */
$numPagesToRetrieve = 9; //Get 100 results, shouldn't really be hardcoded.
$google->query($keywords);
$rankProcessor->process($google->getResults());

while($google->hasMoreResults() && $numPagesToRetrieve > 0){
	$numPagesToRetrieve--;
	$google->getNextPage();
	$rankProcessor->process($google->getResults());
}

App::getInstance()->getLog()->debug("Total results processed = ".$rankProcessor->getNumProcessed());
App::getInstance()->getLog()->debug("Ranking". var_export($rankProcessor->getRanking(), true));

/*
 * Now we have performed the analysis we can return the result
 * 
 * The api is returning a mix of json and html to make the display on the frontend easier 
 * 
 * Use some standard templates to populate with info.
 * 
 */

$ranking = $rankProcessor->getRanking();

$templateFound = "<p>%s was <strong>found</strong> at the following positions within the top %s Google results:</p><ul>%s</ul>";
$templateNotFound = "<p>%s was <strong>not found</strong> while searching the top %s Google results.</p>";

/*
 * Check if we actually processed any results, if not, something is wrong
 * The Google class should probably throw some exceptions if results can't be returned 
 */

if($rankProcessor->getNumProcessed() == 0){
	http_response_code(502); //api should have standard mechanism for setting response code, should not be manual
	$msg = "Unable to retrieve results from Google.".PHP_EOL.PHP_EOL;
	//Add the error from google if it's there
	if(isset($google->getResponse()->error)){
		$msg .= print_r($google->getResponse()->error, true);
	}
	$response = ["success" => false, "html" => $msg];
}elseif(empty($ranking)){
	$html = sprintf($templateNotFound, $targetUrl, $rankProcessor->getNumProcessed());
	$response = ["success" => true, "html" => $html];
}else{
	$list = "<li>".implode("</li><li>", $ranking)."</li>";
	$html = sprintf($templateFound, $targetUrl, $rankProcessor->getNumProcessed(), $list);
	$response = ["success" => true, "html" => $html];
}

/*
 * Return the response as a json object.
 */
header("Content-Type:application/json");
echo json_encode($response);	


