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

	public function removeRecord() {
		// NOt implemented.
	}

	// public function getRecord($id) {
	// 	return $this->dal->getRecordById($id);
	// }
}