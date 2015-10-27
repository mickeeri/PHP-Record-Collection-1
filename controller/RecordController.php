<?php

namespace controller;

/**
 * Responsible for providing records from model classes to views and providing user input to model.
 */
class RecordController {
	
	private $view;
	private $facade; 
	private $navigationView;

	function __construct($view, \view\NavigationView $nv, \model\RecordFacade $rf) {
		$this->view = $view;
		$this->facade = $rf;
		$this->navigationView = $nv;
	}

	/**
	 * Gets records from facade model and provides them to view. 
	 * @return void
	 */
	public function getRecords() {
		$records = $this->facade->getRecords();
		$this->view->setListOfRecords($records);
	}

	/**
	 * Gets record from facade and provides it to view. 
	 * @param  int $recordID 
	 * @return void
	 */
	public function getRecord($recordID) {
		$record = $this->facade->getRecord($recordID);		
		// Set record in ShowRecordView.
		$this->view->setRecord($record);
		
		// If user wants to rate record.
		if ($this->view->getSubmittedRecordRating() !== null) {					
			$this->rateRecord($record);			
		}
	}

	/**
	 * Gets rating from view and passes to facade. 
	 * @param  \model\Record $recordToRate
	 * @return void
	 */
	private function rateRecord(\model\Record $recordToRate) {
		$rating = $this->view->getSubmittedRecordRating();
		$this->facade->addRatingToRecord($recordToRate, $rating);	
		$this->navigationView->refresh();
	}

	/**
	 * Handels removal of record. 
	 * @param  int $recordID id of the record that user wants to delete. 
	 * @return void
	 */
	public function deleteRecord($recordID) {
		$record = $this->facade->getRecord($recordID);
		$this->view->setRecord($record);
		$this->view->setUserWantsToDeleteRecord();

		// If user has answered "Yes" to confirm delete question. 
		if($this->view->userHasConfirmedDelete()) {
			$recordTitle = $record->getTitle();
			$this->facade->removeRecord($record);
			$this->navigationView->redirect(\view\NavigationView::$recordListURL, $recordTitle . \view\Message::$hasBeenDeleted);
		} 
		// If user has regreted deletion just redirect back to current record. 
		elseif ($this->view->userHasDeclinedDelete()) {
			$this->navigationView->redirect(\view\NavigationView::$recordShowURL.'='.$record->getRecordID(), null);
		}
	}

	/**
	 * Handles update of record. 
	 * @param  int $recordID id of record that will be updated. 
	 * @return [type]           [description]
	 */
	public function updateRecord($recordID) {
		$record = $this->facade->getRecord($recordID);		
		// Provides record to update to view. 
		$this->view->setRecord($record);

		// If user has pressed submit button.
		if ($this->view->userWantsToSaveRecord()) {				
			
			// getNewRecord returns \model\Record object. 
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
			
			// Gets Record object from user input. 
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
}