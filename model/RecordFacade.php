<?php

namespace model;

/**
* 
*/
class RecordFacade {
	
	private $dal;

	function __construct(\model\RecordDAL $recordDAL) {
		$this->dal = $recordDAL;
	}

	public function saveRecord(\model\Record $recordToBeAdded) {

		// If id is null record does not exists in db. 
		if ($recordToBeAdded->getRecordID() === null) {
			$this->dal->add($recordToBeAdded);
		} 
		// Otherwise needs to be updated.
		else {
			$this->dal->updateRecord($recordToBeAdded);
		}		
	}

	public function getRecords() {
		return $this->dal->getRecords();
	}

	// public function updateRecord($record) {
	// 	$this->dal->updateRecord($record);
	// }

	public function removeRecord(\model\Record $record) {
		// Removes cover image from directory.
		$filename = \Settings::PIC_UPLOAD_DIR . $record->getCoverFilePath();
		// TODO: Kolla om filen finns.
		unlink($filename);
	
		// Removes entry in the database.
		$this->dal->removeRecord($record->getRecordID());
	}

	public function getRecord($id) {
		return $this->dal->getRecordById($id);
	}

	public function getLatestRecords() {
		return $this->dal->getLatestRecords();
	}

	/**
	 * Gets values from RecordController.
	 * @param \model\Record $record record to rate
	 * @param int     $rating rating score from 1-5
	 */
	public function addRatingToRecord(\model\Record $record, $rating) {

		// If record already has rating.
		if ($this->getRecordRating($record) !== null) {
			
			// If user selects score that is already set the rating is removed altogether. 
			if ($this->getRecordRating($record) === $rating) {
				$this->dal->removeRating($record->getRecordID());
			} 
			// Just update.
			else {
				$this->dal->updateRecordRating($record, $rating);
			}

			
		} else {
			$this->dal->addRatingToRecord($record, $rating);
		}		
	}

	public function getRecordRating(\model\Record $record) {
		return $this->dal->getRating($record);
	}
}