<?php

namespace tests\CreditorWatch\Transport;

use CreditorWatch\System\App;

require_once TEST_ROOT.DIRECTORY_SEPARATOR.'TestCase.php';

class GoogleTest extends \tests\TestCase {

	/**
	 * TODO: Turn into mock tests so api limit is not wasted
	 * 
	 * Since we are using the strategy patter, we could just use a mock
	 * Http transport which would not actually make any http requests. We would
	 * need at least one request to know what format Google returns their response in
	 * and programmatically change values to make it look dynamic.
	 * 
	 */

	public function basicQueryTest(){

		$transportStrategy = new \CreditorWatch\Transport\Http();
		$parserStrategy = new \CreditorWatch\Parser\Json();
		$google = new \CreditorWatch\SearchEngine\Google($transportStrategy, $parserStrategy);

		$google->query("creditor watch");
		$results = $google->getResults();

		assert(is_array($results));
		assert(count($results) == 10);
		assert($results[0]->kind == 'customsearch#result');
		
	}

	public function canGetNextPageTest(){

		$transportStrategy = new \CreditorWatch\Transport\Http();
		$parserStrategy = new \CreditorWatch\Parser\Json();
		$google = new \CreditorWatch\SearchEngine\Google($transportStrategy, $parserStrategy);

		$google->query("creditor watch");
		//Skip straight to the next page
		$google->getNextPage();
		$response = $google->getResponse();

		App::getInstance()->getLog()->debug("Received results: ".var_export($response->queries,1));

		assert(is_object($response));
		assert($response->kind == 'customsearch#search');
		assert($response->queries->previousPage[0]->startIndex == 1); 
		assert($response->queries->request[0]->startIndex == 11); //Api serves 10 results at a time
		assert($response->queries->nextPage[0]->startIndex == 21);

	}

}