<?php

namespace model;

/**
* 
*/
class Order {
	
	private $orderID;
	private $customerID;
	private $records; //array

	function __construct($customerID) {
		$this->customerID;
	}

	public function setOrderID($orderID) {
		$this->orderID = $orderID;
	}

	public function getRecordsInOrder() {
		return $this->records;
	}


}