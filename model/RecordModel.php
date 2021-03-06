<?php

namespace model;

class NoTitleException extends \Exception {};
class NoArtistException extends \Exception {};
class WrongReleaseYearException extends \Exception {};
class NoDescriptionException extends \Exception {};
class WrongPriceException extends \Exception {};
class WrongRatingException extends \Exception {};
class InvalidFileTypeException extends \Exception {};
class ImageUploadException extends \Exception {};
class MissingRelaseYearException extends \Exception {};
class StringTooLongException extends \Exception {};
class InvalidFileSizeException extends \Exception {};
class InvalidCharException extends \Exception {};

class Record {
	
	private $recordID;
	private $title; 
	private $artist;
	private $releaseYear;
	private $description;
	private $cover;
	private $rating;

	private static $defaultCoverFileName = "default.png";

	function __construct($title, $artist, $releaseYear, $description, $cover) {

		if (is_string($title) === false || $title === "") {
			throw new NoTitleException();
		}

		if (is_string($artist) === false || $artist === "") {
			throw new NoArtistException();
		}

		if ($releaseYear === "") {
			throw new MissingRelaseYearException();
		}

		// Checks if release year is number between 1900 and current year. 
		if (is_numeric($releaseYear) === false || $releaseYear < 1900 || $releaseYear > date("Y")) {
			throw new WrongReleaseYearException();
		}

		if (is_string($description) === false || $description === "") {
			throw new NoDescriptionException();
		}

		$this->checkStringLenght($title);
		$this->checkStringLenght($artist);
		$this->checkStringLenght($description);

		$this->checkForInvalidChar($title);
		$this->checkForInvalidChar($artist);
		$this->checkForInvalidChar($releaseYear);
		$this->checkForInvalidChar($description);
		
		
		/**
		 * Check if $cover is simple string (file name) and therefore already in database. 
		 * Otherwise image file needs validation and to be saved to picture directory.
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
	}

	/**
	 * Throws exception if input contains invalid characthers such as script tags. 
	 * @param  string $string input
	 */
	private function checkForInvalidChar($string) {
		if (filter_var($string, FILTER_SANITIZE_STRING) !== $string) {
			throw new InvalidCharException();
		}
	}

	/**
	 * Validates string length.
	 */
	private function checkStringLenght($string) {
		if (mb_strlen($string) > 100) {
			throw new StringTooLongException();
		}
	}

	public function setRecordID($recordID) {
		
		if ($recordID !== null && is_numeric($recordID) === false) {
			throw new \Exception();
		}

		$this->recordID = $recordID;
	}

	public function setRecordRating($rating) {
		
		// Ensures rating is between 1 and 5 if not null.
		if ($rating !== null && $rating < 1 || $rating > 5) {
			throw new \Exception();
		}

		$this->rating = $rating;
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

	public function getCoverFilePath() {
		return $this->cover;
	}

	public function getRating() {
		return $this->rating;
	}

	/**
	 * Validates and save image.
	 * Some code from: http://www.sitepoint.com/file-uploads-with-php/
	 * @param  array $image information of image uploaded via HTTP POST $_FILES.
	 * @return string $name sanatized file name
	 */
	private function validateAndSaveCoverFile($image) {

		// Use default image if no image is uploaded. 
		if ($image["name"] === "") {
			
			$name = self::$defaultCoverFileName;
		
		} else {

			// Create directory for storing pics if it don't exist. 
			if (!file_exists(\Settings::PIC_UPLOAD_DIR)) {
			    mkdir(\Settings::PIC_UPLOAD_DIR, 0777, true);
			}
			
			// If there is error. 
			if ($image["error"] !== UPLOAD_ERR_OK) {
				throw new \ImageUploadException();
			}		

			// Checking the file type.
			$fileType = exif_imagetype($image["tmp_name"]);
			$allowed = array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG);
			if (!in_array($fileType, $allowed)) {
			    throw new InvalidFileTypeException();
			}

			// Checking that file size don't exceed 0.5MB. 
			if ($image["size"] > 524288) {
				throw new InvalidFileSizeException();
			}

			// Ensures that file name is safe.
			$name = preg_replace("/[^A-Z0-9._-]/i", "_", $image["name"]);
			
			// Don't overwrite an existing file
			$i = 0;
			$parts = pathinfo($name);
			while (file_exists(\Settings::PIC_UPLOAD_DIR . $name)) {
			    $i++;
			    $name = $parts["filename"] . "-" . $i . "." . $parts["extension"];
			}
			
			// Preserve file from temporary directory
			$success = move_uploaded_file($image["tmp_name"], \Settings::PIC_UPLOAD_DIR . $name);
			if (!$success) {
				throw new \ImageUploadException();
			}
			
			// Set proper permissions on the new file
			chmod(\Settings::PIC_UPLOAD_DIR . $name, 0644);
		}

		return $name;
	}
}