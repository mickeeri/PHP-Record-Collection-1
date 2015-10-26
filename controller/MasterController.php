<?php

namespace controller;

require_once("view/HomeView.php");

// Record views
require_once("view/NewRecordView.php");
require_once("view/IndexRecordView.php");
require_once("view/ShowRecordView.php");



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

		// DALS and facades.
		$this->recordDAL = new \model\RecordDAL($this->mysqli);
		//$this->orderDAL = new \model\OrderDAL($this->mysqli);
		$this->recordFacade = new \model\RecordFacade($this->recordDAL);
		//$this->orderFacade = new \model\OrderFacade($this->orderDAL);
	}


	/**
	 * Selects views and controllers based on input.
	 */
	public function handleInput() {
		
		if ($this->navigationView->onLoginPage()) {
			# code...
		} elseif ($this->view->onRegistrationPage()) {
			# code...
		} else {
			// CREATE
			if ($this->navigationView->onNewRecordPage()) {
				$this->view = new \view\NewRecordView();
				$controller = new \controller\RecordController($this->view, $this->navigationView, $this->recordFacade);
				$controller->addRecord();			
			} 

			// UPDATE
			elseif ($this->navigationView->onUpdateRecordPage()) {
				$this->view = new \view\NewRecordView();
				$controller = new \controller\RecordController($this->view, $this->navigationView, $this->recordFacade);			
				$recordID = $this->navigationView->getRecordToShow();
				//$controller->getRecord($recordID);						
				$controller->updateRecord($recordID);	
			}
			
			// READ ALL
			elseif ($this->navigationView->onRecordListPage()) {
				$this->view = new \view\IndexRecordView();
				$controller = new \controller\RecordController($this->view, $this->navigationView, $this->recordFacade);
				$controller->getRecords();
			} 

			// READ ONE
			elseif ($this->navigationView->onRecordShowPage()) {
				
				$this->view = new \view\ShowRecordView();
				$controller = new \controller\RecordController($this->view, $this->navigationView, $this->recordFacade);
				$recordID = $this->navigationView->getRecordToShow();
				$controller->getRecord($recordID);			
			}

			// DELETE
			elseif ($this->navigationView->onDeleteRecordPage()) {
				$this->view = new \view\ShowRecordView();
				$controller = new \controller\RecordController($this->view, $this->navigationView, $this->recordFacade);
				$recordID = $this->navigationView->getRecordToShow();
				$controller->deleteRecord($recordID);
			}

			elseif ($this->navigationView->wantsToRateRecord()) {
				$this->view = new \view\ShowRecordView();
				$controller = new \controller\RecordController($this->view, $this->navigationView, $this->recordFacade);
				$rating = $this->navigationView->getRecordRating();
				$recordID = $this->navigationView->getCurrentRecordIDFromCookie();
				$controller->rateRecord($rating, $recordID);			
			}
			
			// HOME PAGE
			else {
				$this->view = new \view\HomeView();
				$controller = new \controller\HomeController($this->view, $this->navigationView, $this->recordFacade);		
				$controller->doHome();
			}
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