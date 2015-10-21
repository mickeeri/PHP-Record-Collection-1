<?php

namespace model;

class NoTitleException extends \Exception {};
class NoArtistException extends \Exception {};
class WrongReleaseYearException extends \Exception {};
class NoDescriptionException extends \Exception {};
class WrongPriceException extends \Exception {};

class Record {
	
	private $recordID;
	private $title; 
	private $artist;
	private $releaseYear;
	private $description;
	private $price;

	
	function __construct($title, $artist, $releaseYear, $description, $price) {

		// if (is_string($title) === false || $title === "") {
		// 	throw new NoTitleException();
		// }

		// if (is_string($artist) === false || $artist === "") {
		// 	throw new NoArtistException();
		// }

		// if (is_numeric($releaseYear) === false || $releaseYear < 1900 || $releaseYear > 2000) {
		// 	throw new WrongReleaseYearException();
		// }

		// if (is_string($description) === false || $description === "") {
		// 	throw new NoDescriptionException();
		// }

		// if (is_numeric($price) === false || $price < 0 || $price > 10000) {
		// 	throw new WrongPriceException();
		// }

		$this->title = $title;
		$this->artist = $artist;
		$this->releaseYear = $releaseYear;
		$this->description = $description;
		$this->price = $price;
	}

	public function setRecordID($recordID) {
		$this->recordID = $recordID;
	}

	public function getRecordID() {
		return $this->recordID;
	}

	public function getTitle() {
		return $this->title;
	}

	public function getArtist() {
		return $this->artist;		
	}

	public function getReleaseYear() {
		return $this->releaseYear;
	}

	public function getDescription() {
		return $this->description;
	}

	public function getPrice() {
		return $this->price;
	}
}