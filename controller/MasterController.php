<?php

namespace controller;

require_once("view/HomeView.php");
require_once("view/RecordView.php");

require_once("controller/HomeController.php");
require_once("controller/RecordController.php");

require_once("model/RecordModel.php");
require_once("model/RecordFacade.php");
require_once("model/RecordDAL.php");

require_once("DBSettings.php");

class MasterController {
	
	private $navigationView;

	public function __construct(\view\NavigationView $navigationView) {
		// Setting up database.
		$this->mysqli = new \mysqli(\DbSettings::HOST, \DbSettings::USERNAME, \DbSettings::PASSWORD, \DbSettings::DATABASE);
		if(mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		}

		$this->navigationView = $navigationView;

		// Record models.
		$this->recordDAL = new \model\RecordDAL($this->mysqli);
		$this->recordFacade = new \model\RecordFacade($this->recordDAL);

	}


	/**
	 * Selects views and controllers based on input.
	 */
	public function handleInput() {
		if ($this->navigationView->onNewRecordPage()) {
			$this->view = new \view\RecordView();
			$controller = new \controller\RecordController($this->view, $this->recordFacade);
			$controller->addRecord();			
		} else {
			$controller = new \controller\HomeController();
			$this->view = new \view\HomeView();
		}

		$this->mysqli->close();		
	}

	/**
	 * Returns view selected in handleInput().
	 * @return \view\...
	 */
	public function generateOutput() {
		return $this->view;
	}
}