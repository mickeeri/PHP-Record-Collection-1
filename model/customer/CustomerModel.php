<?php

namespace model;

class Customer {
	
	private customerID;
	private name;
 	private email;

	function __construct($name, $email) {	
		
		$this->name = $name;
		$this->email = $email;
	}

	public function setCustomerID($id) {
		$this->customerID = $id;
	}

	public function getCustomerID() {
		return $this->customerID;
	}

	public function getName() {
		return $this->name;
	}

	public function getEmail() {
		return $this->email;
	}
}