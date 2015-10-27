<?php

namespace controller;

/**
* Controller for start page.
*/
class HomeController {
	
	private $view;
	private $navigationView;
	private $recordFacade;

	function __construct(\view\HomeView $v, \view\NavigationView $nv, \model\RecordFacade $rf) {
		$this->view = $v;
		$this->navigationView = $nv;
		$this->recordFacade = $rf;
	}

	public function doHome() {
		// Provides array of latest records to view.
		$this->view->setLatestRecords($this->recordFacade->getLatestRecords());
	}
}