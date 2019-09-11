<?php

namespace CreditorWatch\SearchEngine;

abstract class AbstractSearchEngine {

	protected $transportStrategy;
	protected $parserStrategy;
	
	public function __construct($transportStrategy, $parserStrategy){
		$this->transportStrategy = $transportStrategy;
		$this->parserStrategy = $parserStrategy;
	}

	abstract public function query($searchTerms);
	abstract public function getResults();
	abstract public function hasMoreResults();
	abstract public function getNextPage();
}
