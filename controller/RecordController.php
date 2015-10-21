<?php

namespace controller;

class RecordController {
	
	private $view;
	private $facade; 

	function __construct($view, \model\RecordFacade $rf) {
		$this->view = $view;
		$this->facade = $rf;
	}

	public function getRecords() {
		$records = $this->facade->getRecords();
		$this->view->setListOfRecords($records);
	}

	public function getRecord($recordID) {
		$record = $this->facade->getRecord($recordID);
		$this->view->setRecord($record);
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