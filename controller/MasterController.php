<?php

namespace controller;

require_once("view/HomeView.php");
require_once("view/RecordView.php");
require_once("controller/HomeController.php");
require_once("controller/RecordController.php");

class MasterController {
	
	private $navigationView;

	public function __construct(\view\NavigationView $navigationView) {
		$this->navigationView = $navigationView;
	}


	public function handleInput() {
		if ($this->navigationView->onNewRecordPage()) {
			$controller = new \controller\RecordController();
			$this->view = new \view\RecordView();
		} else {
			$controller = new \controller\HomeController();
			$this->view = new \view\HomeView();
		}
		
	}

	public function generateOutput() {
		return $this->view;
	}
}