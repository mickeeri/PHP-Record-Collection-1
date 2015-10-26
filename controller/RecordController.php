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
		// Set record in ShowRecordView.
		$this->view->setRecord($record);
		
		// If user wants to rate record.
		if ($this->view->getSubmittedRecordRating() !== null) {
						
			$this->rateRecord($record);
			
		}
	}

	private function rateRecord(\model\Record $recordToRate) {
		$rating = $this->view->getSubmittedRecordRating();
		$this->facade->addRatingToRecord($recordToRate, $rating);	
		$this->navigationView->refresh();
	}

	public function deleteRecord($recordID) {
		$record = $this->facade->getRecord($recordID);
		$this->view->setRecord($record);
		$this->view->setUserWantsToDeleteRecord();

		if($this->view->userHasConfirmedDelete()) {
			$recordTitle = $record->getTitle();
			$this->facade->removeRecord($record);
			$this->navigationView->redirect(\view\NavigationView::$recordListURL, $recordTitle . \view\Message::$hasBeenDeleted);
		} elseif ($this->view->userHasDeclinedDelete()) {
			$this->navigationView->redirect(\view\NavigationView::$recordShowURL.'='.$record->getRecordID());
		}
	}

	public function updateRecord($recordID) {
		$record = $this->facade->getRecord($recordID);
		$this->view->setRecord($record);

		// If user has pressed submit button.
		if ($this->view->userWantsToSaveRecord()) {				
			
			$record = $this->view->getNewRecord();

			if ($record !== null) {
				try {
					$this->facade->saveRecord($record);
					$this->navigationView->redirect(\view\NavigationView::$recordShowURL.'='.$record->getRecordID(), 
						\view\Message::$hasBeenUpdated);
				} catch (\Exception $e) {
					$this->view->setErrorMessage(\view\Message::$generalError);
				}
			}
		}
	}

	public function addRecord() {
		// If user has pressed submit button.
		if ($this->view->userWantsToSaveRecord()) {				
			
			$record = $this->view->getNewRecord();

			if ($record !== null) {
				try {
					$this->facade->saveRecord($record);
					$this->view->isRecordSaved = true;
				} catch (\Exception $e) {
					$this->view->setErrorMessage(\view\Message::$generalError);
				}
			}
		}
	}

	// public function rateRecord($rating, $recordID) {
		
	// 	var_dump($rating);

	// 	var_dump($recordID);

	// 	$this->facade->addRatingToRecord($rating, $recordID);


	// 	$this->navigationView->redirect(\view\NavigationView::$recordShowURL.'='.$recordID, 
	// 		"The album has been rated $rating.");
	// }
}