<?php

namespace controller;

require_once("view/HomeView.php");

class MasterController {
	public function __construct() {

	}


	public function handleInput() {
		$this->view = new \view\HomeView();
	}

	public function generateOutput() {
		return $this->view;
	}
}