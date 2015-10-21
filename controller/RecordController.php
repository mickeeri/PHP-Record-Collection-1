<?php

namespace controller;

class RecordController {
	
	private $recordView;
	private $facade; 

	function __construct(\view\RecordView $rv, \model\RecordFacade $rf) {
		$this->recordView = $rv;
		$this->facade = $rf;
	}

	public function addRecord() {
		// If user has pressed submit button.
		if ($this->recordView->userWantsToAddRecord()) {				
			
			$record = $this->recordView->getNewRecord();

			if ($record !== null) {
				try {
					$this->facade->saveRecord($record);
					$this->recordView->isRecordSaved = true;
				} catch (\Exception $e) {
					// Do something.
				}
			}
		}
	}
}