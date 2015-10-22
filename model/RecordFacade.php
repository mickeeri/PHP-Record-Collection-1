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
		
		$this->dal->add($recordToBeAdded);
	}

	public function getRecords() {
		return $this->dal->getRecords();
	}

	public function updateRecord($record) {
		$this->dal->updateRecord($record);
	}

	public function removeRecord(\model\Record $record) {
		// Removes cover image from directory.
		$filename = \Settings::PIC_UPLOAD_DIR . $record->getCoverFilePath();
		unlink($filename);

		// Removes entry in the database.
		$this->dal->removeRecord($record->getRecordID());
	}

	public function getRecord($id) {
		return $this->dal->getRecordById($id);
	}
}