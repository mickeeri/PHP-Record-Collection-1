<?php

namespace model;


/**
* 
*/
class OrderFacade {
	
	private $orderDAL;

	function __construct(\model\OrderDAL $od) {
		$this->orderDAL = $od;
	}

	public function addRecordToOrder($record) {
		$this->orderDAL->add($record);
	}
}