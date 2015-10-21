<?php

namespace model;

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
}