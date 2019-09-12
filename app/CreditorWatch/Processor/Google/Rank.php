<?php

namespace CreditorWatch\Processor\Google;

use CreditorWatch\System\App;

/*
 * TODO: Extend the processor functionality to use the chain of responsibility
 * design pattern. This way we can link together multiple interpretations of the
 * same data and seperate operations on how to interpret the data;
 */

class Rank {

	protected $rank = [];
	protected $targetUrl;
	protected $google;
	protected $numProcessed = 0;

	public function __construct(\CreditorWatch\SearchEngine\Google $google, $targetUrl) {
		$this->google = $google;
		$this->targetUrl = $targetUrl;
	}

	/*
	 * May want to clarify how the CEO wants to search, is it strictly the domain name
	 * or are subdomains included? ie help.at.creditorwatch.com.au.
	 */
	
	public function process($data) {
		App::getInstance()->getLog()->debug("Processing Google result data");
		App::getInstance()->getLog()->debug("Google pageIndex=".$this->google->getPageIndex());
		foreach($data as $key => $item){
			$this->numProcessed++;
			App::getInstance()->getLog()->debug("Searching item for url '$this->targetUrl'");
			App::getInstance()->getLog()->debug("Item link=".$item->link);
			App::getInstance()->getLog()->debug("Item displayLink=".$item->displayLink);
			if($item->link == $this->targetUrl || $item->displayLink == $this->targetUrl){
				$rank = $this->google->getPageIndex() + $key;
				$this->rank[] = $rank;
				App::getInstance()->getLog()->info("Found match at position $key absolute rank = $rank");
			}	
		}
		return $this;
	}

	public function getRanking(){
		return $this->rank;
	}

	public function getNumProcessed(){
		return $this->numProcessed;
	}
}
