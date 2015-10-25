<?php

namespace model;

class RecordDontExistException extends \Exception {};

class RecordDAL {
	
	private static $recordTable = "record";
	//private static $recordTable = "Record";
	private static $ratingTable = "rating";

	function __construct(\mysqli $db) {
		$this->database = $db;
	}

	public function add(\model\Record $recordToBeAdded) {

		// TODO: Förenkla prepeare statement.
		$stmt = $this->database->prepare("INSERT INTO `" . \DbSettings::DATABASE .  "`.`" . self::$recordTable . "`(
			`title`, `artist`, `releaseYear`, `description`, `price`, `cover`) 
				VALUES (?, ?, ?, ?, ?, ?)");

		if ($stmt === false) {
			throw new \Exception($this->database->error);
		}

		$title = $recordToBeAdded->getTitle();
		$artist = $recordToBeAdded->getArtist();
		$releaseYear = $recordToBeAdded->getReleaseYear();
		$description = $recordToBeAdded->getDescription();
		$price = $recordToBeAdded->getPrice();
		$cover = $recordToBeAdded->getCoverFilePath();

		$stmt->bind_param('ssssds', $title, $artist, $releaseYear, $description, $price, $cover);
		
		$stmt->execute();
	}

	public function updateRecord(\model\Record $recordToBeUpdated) {
		/// UPDATE `phpassignment`.`record` SET `artist` = 'Bruce Springsteen' WHERE `record`.`recordID` = 19;

		$stmt = $this->database->prepare("UPDATE " . self::$recordTable . " SET title=?, artist=?, releaseYear=?, 
			description=?, price=?, cover=? WHERE recordID=?");

		if ($stmt === false) {
			throw new \Exception($this->database->error);
		}

		$stmt->bind_param('ssssdsi', $title, $artist, $releaseYear, $description, $price, $cover, $recordID);

		$recordID = $recordToBeUpdated->getRecordID();
		$title = $recordToBeUpdated->getTitle();
		$artist = $recordToBeUpdated->getArtist();
		$releaseYear = $recordToBeUpdated->getReleaseYear();
		$description = $recordToBeUpdated->getDescription();
		$price = $recordToBeUpdated->getPrice();
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

		$stmt->bind_result($recordID, $title, $artist, $releaseYear, $description, $price, $cover);

		while ($stmt->fetch()) {
			$record = new \model\Record($title, $artist, $releaseYear, $description, $price, $cover);
			$record->setRecordID($recordID);
			$records[] = $record;
		}

		// Sets the records rating.
		foreach ($records as $record) {			
			$record->setRecordRating($this->getRating($record));
		}
		
		return $records;
	}

	public function getLatestRecords() {
		// SELECT * from `record` ORDER BY `recordID` DESC LIMIT 2

		$latestRecords = array();

		$stmt = $this->database->prepare("SELECT * FROM " . self::$recordTable . " ORDER BY recordID DESC LIMIT 4");
		
		if ($stmt === false) {
			throw new \Exception($this->database->error);
		}

		//$stmt->bind_param("i", $recordID);

		$stmt->execute();

		$stmt->bind_result($recordID, $title, $artist, $releaseYear, $description, $price, $cover);

		while ($stmt->fetch()) {
			$record = new \model\Record($title, $artist, $releaseYear, $description, $price, $cover);
			$record->setRecordID($recordID);		
			$latestRecords[] = $record;
		}


		

		// TODO kasta undantag om tom array.

		return $latestRecords;
	}


	/**
	 * Fetches one record from database.
	 * @param  [type] $recordID [description]
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

		$stmt->bind_result($recordID, $title, $artist, $releaseYear, $description, $price, $cover);

		$stmt->fetch();

		$record = new \model\Record($title, $artist, $releaseYear, $description, $price, $cover);
		
		$record->setRecordID($recordID);

		$stmt->close();

		$record->setRecordRating($this->getRating($record));

		return $record;
	}

	public function removeRecord($recordID) {
		$stmt = $this->database->prepare("DELETE FROM `" . self::$recordTable . "` WHERE recordID = ?");

		if ($stmt === false) {
			throw new \Exception($this->database->error);
		}

		$stmt->bind_param("i", $recordID);
		
		$stmt->execute();	

		// Removes record rating as well.
		$this->removeRating($recordID);	
	}

	public function addRatingToRecord(\model\Record $record, $rating) {
		$stmt = $this->database->prepare("INSERT INTO " . self::$ratingTable . "(recordID, rating) VALUES (?, ?)");

		// $stmt = $this->database->prepare("INSERT INTO `" . \DbSettings::DATABASE .  "`.`" . self::$recordTable . "`(
		// 	`title`, `artist`, `releaseYear`, `description`, `price`, `cover`) 
		// 		VALUES (?, ?, ?, ?, ?, ?)");

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
	 * @return [type]                [description]
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

	public function removeRating($recordID) {
		
		$stmt = $this->database->prepare("DELETE FROM " . self::$ratingTable . " WHERE recordID = ?");
	

		if ($stmt === false) {
			throw new \Exception($this->database->error);
		}

		$stmt->bind_param("i", $recordID);
		
		$stmt->execute();	

	}
}