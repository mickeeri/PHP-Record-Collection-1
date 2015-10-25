<?php

namespace view;

class NewOrderView {
	
	private $record;
	private $records = array();

	function __construct() {
		# code...
	}

	public function response() {
		return $this->generateOrderForm();
	}

	private function generateOrderForm() {

	}

	public function setRecord($record) {
		$this->record = $record;
	}
}