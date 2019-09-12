<?php

namespace CreditorWatch\SearchEngine;

use CreditorWatch\System\App;

class Google extends AbstractSearchEngine {

	protected $pageIndex = 1;
	protected $response;
	protected $parsedResponse;
	protected $searchTerms;

	protected function makeRequest(){

		$searchEnginesConfig = App::getInstance()->getConfig('searchEngines');
		$googleConfig = $searchEnginesConfig['google'];		

		$requestData = [
			'endpoint' => $googleConfig['searchEndpoint'],
			'method' => 'GET',
			'queryParams' => [
				'q' => $this->searchTerms
				, 'start' => $this->pageIndex
				, 'key' => $googleConfig['token'] 
				, 'cx' => $googleConfig['engineId'] 
			]  
		];

		return $this->transportStrategy::makeRequest($requestData);
		
	}

	public function query($searchTerms) {

		App::getInstance()->getLog()->info("Querying Google using transport strategy ".get_class($this->transportStrategy));

		$this->searchTerms = $searchTerms;
		$request = $this->makeRequest();
		$this->transportStrategy->sendRequest($request);
		$this->response = $this->transportStrategy->getResponse();	
		App::getInstance()->getLog()->info("Parsing results using strategy ".get_class($this->parserStrategy));
		$this->parsedResponse = $this->parserStrategy->parse($this->response->getResponseData());

		return $this;
	}

	public function getResponse(){
		return $this->parsedResponse;
	}

	public function getResults() {

		$results = false;

		if(!is_null($this->response) && $this->response->isSuccess()){
			$responseData = $this->response->getResponseData();
			App::getInstance()->getLog()->debug("Successful resonse! ". var_export($responseData, true));
			$results = $this->parsedResponse->items; 
		}else{
			App::getInstance()->getLog()->error("Could not get results, no valid response.".var_export($this->response, true));
		}

		return $results;
	}

	public function hasMoreResults() {
		if(is_null($this->parsedResponse)){
			return false;
		}
		
		return isset($this->parsedResponse->queries->nextPage[0]);
	}

	public function getNextPage() {
		$this->pageIndex = $this->parsedResponse->queries->nextPage[0]->startIndex;
		$this->query($this->searchTerms);
		return $this;
	}

	public function getPageIndex(){
		return $this->pageIndex;
	}

}
