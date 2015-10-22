<?php

namespace controller;

class RecordController {
	
	private $view;
	private $facade; 
	private $navigationView;

	function __construct($view, \view\NavigationView $nv, \model\RecordFacade $rf) {
		$this->view = $view;
		$this->facade = $rf;
		$this->navigationView = $nv;
	}

	public function getRecords() {
		$records = $this->facade->getRecords();
		$this->view->setListOfRecords($records);
	}

	public function getRecord($recordID) {
		$record = $this->facade->getRecord($recordID);
		$this->view->setRecord($record);
	}

	public function deleteRecord($recordID) {
		$record = $this->facade->getRecord($recordID);
		$this->view->setRecord($record);
		$this->view->setUserWantsToDeleteRecord();

		if($this->view->userHasConfirmedDelete()) {
			$recordTitle = $record->getTitle();
			$this->facade->removeRecord($record);
			$this->navigationView->redirect(\view\NavigationView::$recordListURL, $recordTitle . "har raderats.");
		}
	}

	public function addRecord() {
		// If user has pressed submit button.
		if ($this->view->userWantsToAddRecord()) {				
			
			$record = $this->view->getNewRecord();

			if ($record !== null) {
				try {
					$this->facade->saveRecord($record);
					$this->view->isRecordSaved = true;
				} catch (\Exception $e) {
					// Do something.
				}
			}
		}
	}
}