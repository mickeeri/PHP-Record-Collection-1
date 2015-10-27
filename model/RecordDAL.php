<?php

namespace model;

class RecordDontExistException extends \Exception {};

class RecordDAL {
	
	private static $ratingTable = "rating";
	private static $recordTable = "record";
	// private static $recordTable = "Record";
	// private static $ratingTable = "Rating";

	function __construct(\mysqli $db) {
		$this->database = $db;
	}

	/**
	 * Add new record to database. 
	 * @param \model\Record $recordToBeAdded
	 */
	public function add(\model\Record $recordToBeAdded) {

		$stmt = $this->database->prepare("INSERT INTO `" . \DbSettings::DATABASE .  "`.`" . self::$recordTable . "`(
			`title`, `artist`, `releaseYear`, `description`, `cover`) 
				VALUES (?, ?, ?, ?, ?)");

		if ($stmt === false) {
			throw new \Exception($this->database->error);
		}

		$title = $recordToBeAdded->getTitle();
		$artist = $recordToBeAdded->getArtist();
		$releaseYear = $recordToBeAdded->getReleaseYear();
		$description = $recordToBeAdded->getDescription();
		$cover = $recordToBeAdded->getCoverFilePath();

		$stmt->bind_param('sssss', $title, $artist, $releaseYear, $description, $cover);
		
		$stmt->execute();
	}

	
	/**
	 * Update of existing record. 
	 * @param  \model\Record $recordToBeUpdated                      
	 */
	public function updateRecord(\model\Record $recordToBeUpdated) {
		
		$stmt = $this->database->prepare("UPDATE " . self::$recordTable . " SET title=?, artist=?, releaseYear=?, 
			description=?, cover=? WHERE recordID=?");

		if ($stmt === false) {
			throw new \Exception($this->database->error);
		}

		$stmt->bind_param('sssssi', $title, $artist, $releaseYear, $description, $cover, $recordID);

		$recordID = $recordToBeUpdated->getRecordID();
		$title = $recordToBeUpdated->getTitle();
		$artist = $recordToBeUpdated->getArtist();
		$releaseYear = $recordToBeUpdated->getReleaseYear();
		$description = $recordToBeUpdated->getDescription();
		$cover = $recordToBeUpdated->getCoverFilePath();

		$stmt->execute();
	}

	/**
	 * Fetches all records from database.
	 * @return array() $records
	 */
	public function getRecords() {
		
		$records = array();

		$stmt = $this->database->prepare("SELECT * FROM " . self::$recordTable);

		if ($stmt === false) {
			throw new \Exception($this->database->error);
		}

		$stmt->execute();

		$stmt->bind_result($recordID, $title, $artist, $releaseYear, $description, $cover);

		while ($stmt->fetch()) {
			$record = new \model\Record($title, $artist, $releaseYear, $description, $cover);
			// Sets record ID. 
			$record->setRecordID($recordID);
			$records[] = $record;
		}

		// Sets the records rating.
		foreach ($records as $record) {			
			$record->setRecordRating($this->getRating($record));
		}
		
		return $records;
	}

	
	/**
	 * Fetches the 4 latest records. 
	 * @return array() $latestRecords
	 */
	public function getLatestRecords() {

		$latestRecords = array();

		$stmt = $this->database->prepare("SELECT * FROM " . self::$recordTable . " ORDER BY recordID DESC LIMIT 4");
		
		if ($stmt === false) {
			throw new \Exception($this->database->error);
		}

		$stmt->execute();

		$stmt->bind_result($recordID, $title, $artist, $releaseYear, $description, $cover);

		while ($stmt->fetch()) {
			$record = new \model\Record($title, $artist, $releaseYear, $description, $cover);
			$record->setRecordID($recordID);		
			$latestRecords[] = $record;
		}

		return $latestRecords;
	}


	/**
	 * Fetches one record from database.
	 * @param  int $recordID 
	 * @return \model\Record record with given id.
	 */
	public function getRecordByID($recordID) {
		
		$stmt = $this->database->prepare("SELECT * FROM `" . self::$recordTable . "` WHERE recordID=?");

		if ($stmt === false) {
			throw new \Exception($this->database->error);
		}

		
		if ($recordID === null) {
			throw new RecordDontExistException();
		}

		$stmt->bind_param("i", $recordID);

		$stmt->execute();

		$stmt->bind_result($recordID, $title, $artist, $releaseYear, $description, $cover);

		$stmt->fetch();

		$record = new \model\Record($title, $artist, $releaseYear, $description, $cover);
		
		$record->setRecordID($recordID);

		$stmt->close();

		// Assings rating
		$record->setRecordRating($this->getRating($record));

		return $record;
	}

	/**
	 * Deletes record from database. 
	 * @param  \model\Record $recordToRemove
	 * @return void
	 */
	public function removeRecord(\model\Record $recordToRemove) {
		$stmt = $this->database->prepare("DELETE FROM `" . self::$recordTable . "` WHERE recordID = ?");

		if ($stmt === false) {
			throw new \Exception($this->database->error);
		}

		$recordID = $recordToRemove->getRecordID();

		$stmt->bind_param("i", $recordID);
		
		$stmt->execute();	

		// Removes record rating as well.
		$this->removeRating($recordToRemove);	
	}

	/**
	 * Adds rating to the table containing recordID and rating. 
	 * @param \model\Record $record record to rate
	 * @param int        $rating number from 1-5
	 */
	public function addRatingToRecord(\model\Record $record, $rating) {
		
		$stmt = $this->database->prepare("INSERT INTO " . self::$ratingTable . "(recordID, rating) VALUES (?, ?)");

		if ($stmt === false) {
			throw new \Exception($this->database->error);
		}

		$stmt->bind_param("ii", $recordID, $rating);

		$recordID = $record->getRecordID();
		$rating = $rating;

		$stmt->execute();

	}

	/**
	 * If record already has been rated this method updates rating.
	 */
	public function updateRecordRating(\model\Record $record, $newRating) {
		
		$stmt = $this->database->prepare("UPDATE " . self::$ratingTable . " SET rating=? WHERE recordID=?");

		if ($stmt === false) {
			throw new \Exception($this->database->error);
		}

		$stmt->bind_param("ii", $rating, $recordID);

		$rating = $newRating;
		$recordID = $record->getRecordID();

		$stmt->execute();

	}

	/**
	 * Fetches score of given record.
	 * @param  \model\Record $record [description]
	 * @return int $rating
	 */
	public function getRating(\model\Record $record) {
		
		if ($record === null) {
			throw new Exception("Record has no rating.");
		}

		$recordID = $record->getRecordID();

		$stmt = $this->database->prepare("SELECT rating FROM " . self::$ratingTable . " WHERE recordID=?");

		if ($stmt === false) {
			throw new \Exception($this->database->error);
		}

		$stmt->bind_param("i", $recordID);

		$stmt->execute();

		$stmt->bind_result($rating);

		$stmt->fetch();

		return $rating;
	}

	/**
	 * Removes rating from record with given id. 
	 * @param  \model\Record $record record to remove rating from
	 * @return [type]           [description]
	 */
	public function removeRating(\model\Record $record) {
		
		$stmt = $this->database->prepare("DELETE FROM " . self::$ratingTable . " WHERE recordID = ?");
	

		if ($stmt === false) {
			throw new \Exception($this->database->error);
		}

		$recordID = $record->getRecordID();

		$stmt->bind_param("i", $recordID);
		
		$stmt->execute();	

	}
}