<?php

namespace model;

class RecordDontExistException extends \Exception {};

class RecordDAL {
	
	private static $recordTable = "record";

	function __construct(\mysqli $db) {
		$this->database = $db;
	}

	public function add(\model\Record $recordToBeAdded) {

		$stmt = $this->database->prepare("INSERT INTO `" . \DbSettings::DATABASE .  "`.`" . self::$recordTable . "`(
			`title`, `artist`, `releaseYear`, `description`, `price`) 
				VALUES (?, ?, ?, ?, ?)");

		if ($stmt === false) {
			throw new \Exception($this->database->error);
		}

		$title = $recordToBeAdded->getTitle();
		$artist = $recordToBeAdded->getArtist();
		$releaseYear = $recordToBeAdded->getReleaseYear();
		$description = $recordToBeAdded->getDescription();
		$price = $recordToBeAdded->getPrice();

		$stmt->bind_param('ssssd', $title, $artist, $releaseYear, $description, $price);
		$stmt->execute();

	}

	public function getRecords() {
		
		$records = array();

		$stmt = $this->database->prepare("SELECT * FROM " . self::$recordTable);

		if ($stmt === false) {
			throw new \Exception($this->database->error);
		}

		$stmt->execute();

		$stmt->bind_result($recordID, $title, $artist, $releaseYear, $description, $price);

		while ($stmt->fetch()) {
			$record = new \model\Record($title, $artist, $releaseYear, $description, $price);
			$record->setRecordID($recordID);
			$records[] = $record;
		}
		
		return $records;
	}

	public function getRecordByID($recordID) {
		
		$stmt = $this->database->prepare("SELECT * FROM `" . self::$recordTable . "` WHERE recordID=?");

		if ($stmt === false) {
			throw new \Exception($this->database->error);
		}

		$stmt->bind_param("i", $recordID);

		$stmt->execute();

		$stmt->bind_result($recordID, $title, $artist, $releaseYear, $description, $price);

		$stmt->fetch();

		if ($recordID === null) {
			throw new RecordDontExistException();
		}

		$record = new \model\Record($title, $artist, $releaseYear, $description, $price);
		$record->setRecordID($recordID);

		return $record;
	}
}