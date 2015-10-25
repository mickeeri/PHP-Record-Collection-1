<?php

namespace controller;


/**
* 
*/
class OrderController {
	
	private $view;
	private $navigationView;
	private $recordsInOrder = array();
	private $orderFacade;

	function __construct($v, \view\NavigationView $nv, \model\OrderFacade $of) {
		$this->view = $v;
		$this->navigationView = $nv;
		$this->orderFacade = $of;
	}

	public function addRecordToOrder($recordToAdd) {

		$this->orderFacade->addRecordToOrder($recordToAdd);
	}

	public function makeOrder() {

	}
}