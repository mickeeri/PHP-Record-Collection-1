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

		if (is_numeric($releaseYear) === false || $releaseYear < 1900 || $releaseYear > 2000) {
			throw new WrongReleaseYearException();
		}

		if (mb_strlen($description) > 140) {
			throw new StringTooLongException();
		}

		if (is_string($description) === false || $description === "") {
			throw new NoDescriptionException();
		}
		
		
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
	}

	public function setRecordID($recordID) {
		$this->recordID = $recordID;
	}

	public function setRecordRating($rating) {
		
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
	 * @return string       sanatized file name
	 */
	private function validateAndSaveCoverFile($image) {

		// Use default image if no image is uploaded. 
		if ($image["name"] === "") {
			$name = self::$defaultCoverFileName;
		} else {


		
			if ($image["error"] !== UPLOAD_ERR_OK) {
				throw new \ImageUploadException();
			}		

			// Checking the file type.
			$fileType = exif_imagetype($image["tmp_name"]);
			$allowed = array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG);
			if (!in_array($fileType, $allowed)) {
			    throw new InvalidFileTypeException();
			}

			var_dump($image["size"]);

			if ($image["size"] > 1048576) {
				throw new InvalidFileSizeException();
			}

			// if (condition) {
			// 	# code...
			// }

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