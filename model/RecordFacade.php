<?php

namespace model;

/**
* A facade between other classes and dal class.
*/
class RecordFacade {
	
	private $dal;

	private static $defaultCoverFileName = "default.png";

	function __construct(\model\RecordDAL $recordDAL) {
		$this->dal = $recordDAL;
	}

	/**
	 * Save record to datbase on either adding of new record or update of existing record. 
	 * @param  \model\Record $recordToBeAdded
	 * @return void
	 */
	public function saveRecord(\model\Record $recordToBeAdded) {

		// If id is null record does not exists in db. 
		if ($recordToBeAdded->getRecordID() === null) {
			$this->dal->add($recordToBeAdded);
		} 
		// Otherwise needs to be updated.
		else {		
			
			// Comparing the new filename with the one already in datbase.
			$oldCoverFileName = $this->getRecord($recordToBeAdded->getRecordID())->getCoverFilePath();

			// If they don't match the old file is removed.
			if ($oldCoverFileName !== $recordToBeAdded->getCoverFilePath() && $oldCoverFileName !== self::$defaultCoverFileName) {
				unlink(\Settings::PIC_UPLOAD_DIR . $oldCoverFileName);
			}

			$this->dal->updateRecord($recordToBeAdded);
		}		
	}


	public function getRecords() {
		return $this->dal->getRecords();
	}

	public function removeRecord(\model\Record $record) {
		
		// Removes cover image from directory as long as not default pic. 
		if ($record->getCoverFilePath() !== self::$defaultCoverFileName) {			
			$filename = \Settings::PIC_UPLOAD_DIR . $record->getCoverFilePath();
			unlink($filename);
		}

		// Removes entry in the database.
		$this->dal->removeRecord($record);
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
				$this->dal->removeRating($record);
			} 
			// Just update rating.
			else {
				$this->dal->updateRecordRating($record, $rating);
			}

		// Add rating.	
		} else {
			$this->dal->addRatingToRecord($record, $rating);
		}		
	}

	/**
	 * Get rating of given $record.
	 * @param  \model\Record $record 
	 * @return int $rating from 1-5
	 */
	public function getRecordRating(\model\Record $record) {
		return $this->dal->getRating($record);
	}
}