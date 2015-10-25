<?php

namespace model;

class OrderDAL {
	
	private database;
	private static $orderTable = "order";
	private static $customerTable = "customer";
	private static $customer;
	
	function __construct(\mysqli $db) {
		$this->database = $db;
		$this->customer = new \model\Customer("Mikael Eriksson", "micke_eri@hotmail.com");
	}

	public function add($recordToAdd) {
		$this->addCustomer();

		$this->addCustomerToOrder($this->customer);

		$stmt = $this->database->prepare("INSERT INTO " . self::$customerTable . " orderID=?, recordID=?");
	}

	public function addCustomerToOrder($customer) {
		
		$stmt = $this->database->prepare("INSERT INTO " . self::$orderTable . "recordID=customerID");

		$stmt->bind_param('i', $customerID);

		$customerID = $customer->getCustomerID();

		$stmt->execute();
	}

	public function addCustomer() {
		//$customer = $this->customer;

		$stmt = $this->database->prepare("INSERT INTO " . self::$customerTable . " name=?, email=?");

		$stmt->bind_param('ss', $name, $email);

		$name = $this->customer->getName();
		$email = $this->customer->getEmail();

		$stmt->execute();
		
	}

	public function getCustomer() {

	}




}