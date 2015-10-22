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
	private $cover;


	
	function __construct($title, $artist, $releaseYear, $description, $price, $cover) {

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
		// 
		
		/**
		 * Check if $cover is simple string (file path) and therefore already in database. 
		 * Otherwise image file needs validation and to be saved to folder.
		 */
		if (is_string($cover) === false) {
			// Calls method to save and store picture file. Method returns name of file.
			$cover = $this->validateAndSaveCoverFile($cover);
		} 
				
		$this->cover = $cover;
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

	public function getCoverFilePath() {
		return $this->cover;
	}

	/**
	 * [validateAndSaveCoverFile description]
	 * @param  [type] $image [description]
	 * @return [type]        [description]
	 */
	private function validateAndSaveCoverFile($image) {

		// http://www.sitepoint.com/file-uploads-with-php/
	
		if ($image["error"] !== UPLOAD_ERR_OK) {
			throw new \Exception();
		}

		// verify the file type
		$fileType = exif_imagetype($image["tmp_name"]);
		$allowed = array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG);
		if (!in_array($fileType, $allowed)) {
		    throw new InvalidFileTypeException();
		}

		// ensure a safe filename
		$name = preg_replace("/[^A-Z0-9._-]/i", "_", $image["name"]);
		
		// don't overwrite an existing file
		$i = 0;
		$parts = pathinfo($name);
		while (file_exists(\Settings::PIC_UPLOAD_DIR . $name)) {
		    $i++;
		    $name = $parts["filename"] . "-" . $i . "." . $parts["extension"];
		}
		
		// preserve file from temporary directory
		$success = move_uploaded_file($image["tmp_name"], \Settings::PIC_UPLOAD_DIR . $name);
		if (!$success) {
			throw new \Exception("Unable to save file");
		}
		
		// set proper permissions on the new file
		chmod(\Settings::PIC_UPLOAD_DIR . $name, 0644);
		//echo "<p>Uploaded file saved as " . $name . ".</p>";

		return $name;
	}
}