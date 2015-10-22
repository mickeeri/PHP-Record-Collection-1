<?php

namespace model;

class RecordDontExistException extends \Exception {};

class RecordDAL {
	
	private static $recordTable = "record";

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

		var_dump($stmt);

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
		
		return $records;
	}

	public function getLatestRecords() {
		// SELECT * from `record` ORDER BY `recordID` DESC LIMIT 2

		$latestRecords = array();

		$stmt = $this->database->prepare("SELECT * FROM record ORDER BY recordID DESC LIMIT 4");
		
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

		$stmt->bind_param("i", $recordID);

		$stmt->execute();

		$stmt->bind_result($recordID, $title, $artist, $releaseYear, $description, $price, $cover);

		$stmt->fetch();

		if ($recordID === null) {
			throw new RecordDontExistException();
		}

		$record = new \model\Record($title, $artist, $releaseYear, $description, $price, $cover);
		$record->setRecordID($recordID);

		return $record;
	}

	public function removeRecord($recordID) {
		$stmt = $this->database->prepare("DELETE FROM `" . self::$recordTable . "` WHERE recordID = ?");
		$stmt->bind_param("i", $recordID);
		$stmt->execute();		
	}



}