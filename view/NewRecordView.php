<?php

namespace view;

/**
* Class used for both registration of new record but also to update existing records.
*/
class NewRecordView {
		
	// Input field ID's
	private static $titleInputId = "title";
	private static $artistInputId = "artist";
	private static $releaseYearInputId = "year";
	private static $descriptionInputId = "description";
	private static $priceInputId = "price";
	private static $coverInputId = "cover";
	private static $submitPostId = "save";

	// Messages
	private $errorMessage = "";
	private $successMessage = "";
	
	// True if record has just been successfully.
	public $isRecordSaved = false;

	/**
	 * @var \model\Record set if request is update of existing album, null if new album. 
	 */
	private $record;

	// function __construct() {

	// }

	/**
	 * Returns HTML response
	 */
	public function response() {

		if ($this->isRecordSaved) {
			$this->successMessage = \view\Message::$hasBeenAdded;
		}

		$response = $this->getNewRecordForm();

		return $response;
	}

	/**
	 * Renders form for adding new record or updating existing album.
	 * @return string HTML
	 */
	private function getNewRecordForm() {

		if ($this->isUpdate()) {
			$header = "Edit album";
		} else {
			$header = "Add new record";
		}

		return '
			<div class="col-md-6">
				<h2>'. $header . '</h2>
				' . $this->showErrorMessage() . '
				' . $this->showSuccessMessage() . '
				<form method="post" enctype="multipart/form-data">'.			
					$this->getInputField("Title", self::$titleInputId, "text") .
					$this->getInputField("Artist", self::$artistInputId, "text") .
					$this->getInputField("Release year", self::$releaseYearInputId, "text") .
					$this->getInputField("About", self::$descriptionInputId, "textarea") .
					$this->getInputField("Upload cover", self::$coverInputId, "file") 
					.'<input class="btn btn-success" name="' . self::$submitPostId . '" type="submit" value="Save">
				</form>
			</div>
		';
	}

	/**
	 * Renders text fields for user input.
	 * @param  string $title input value
	 * @param  string $name  input name and id
	 */
	private function getInputField($title, $name, $type){

		$value = $this->getPostField($name);
			
		return "
			<div class='form-group'>
				<label for='$name'>$title</label>
				<input class='form-control' id='$name' type='$type' value='$value' name='$name' >
			</div>
			";
	}


	/**
	 * Get value of specific field.
	 * @param  string $field fields name
	 * @return string        field value
	 */
	private function getPostField($field){
		
		// If update get values from existing album.
		if ($this->isUpdate()) {			
			if ($field === self::$titleInputId) {
				return trim($this->record->getTitle());
			} if ($field === self::$artistInputId) {
				return trim($this->record->getArtist());
			} if ($field === self::$releaseYearInputId) {
				return trim($this->record->getReleaseYear());
			} if ($field === self::$descriptionInputId) {
				return trim($this->record->getDescription());
			} 
		}
		// Else get value from just filled in input.
		elseif (isset($_POST[$field]) && $this->isRecordSaved === false) {			
			// Trims and removes special chars. 
			return filter_var(trim($_POST[$field]), FILTER_SANITIZE_STRING);
		}

		return  "";
	}

	/**
	 * @return string HTML error message if there is one.
	 */
	private function showErrorMessage() {
		
		if ($this->errorMessage === "") {
			return false;
		}

		return '
			<div class="alert alert-danger" role="alert">
				<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
				<span class="sr-only">Error:</span>
				' . $this->errorMessage . '
			</div>
		';
	}

	/**
	 * @return string HTML success message if there is one.
	 */
	private function showSuccessMessage() {
		
		if ($this->successMessage === "") {
			return false;
		}

		return '
			<div class="alert alert-success" role="alert">
				<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
				<span class="sr-only">Error:</span>
				' . $this->successMessage . '
			</div>
		';
	}

	/**
	 * @return boolean true if user has pressed submit button.
	 */
	public function userWantsToSaveRecord() {
		return isset($_POST[self::$submitPostId]);
	}

	/**
	 * Get the input via $_POST method and creates new Record object.
	 * @return \model\Record $record, null if input doesn't pass validation.
	 */
	public function getNewRecord() {
		
		$title = $_POST[self::$titleInputId];
		$artist = $_POST[self::$artistInputId];
		$releaseYear = $_POST[self::$releaseYearInputId];
		$description = $_POST[self::$descriptionInputId];
		
		if ($this->isUpdate()) {
			
			// Get the old record id.
			$recordID = $this->record->getRecordID();

			// If file name is empty string use the same album cover. 
			if ($_FILES[self::$coverInputId]["name"] === "") {
				$cover = $this->record->getCoverFilePath();
			} 
			// Otherwise the user wants to upload another cover. 
			else {				
				$cover = $_FILES[self::$coverInputId];
			}

		} 
		// If not update upload new cover. Record still doesn't have id.
		else {
			$cover = $_FILES[self::$coverInputId];
			$recordID = null;
		}				
		try {										
			// Creates new record object and returns it. 
			$record = new \model\Record($title, $artist, $releaseYear, $description, $cover);
			$record->setRecordID($recordID);

			return $record;

		// Handles exceptions thrown in RecordModel
		} catch (\model\NoTitleException $e) {
			$this->errorMessage = \view\Message::$missingTitle;
		} catch (\model\NoArtistException $e) {
			$this->errorMessage = \view\Message::$missingArtist;
		} catch (\model\WrongReleaseYearException $e) {
			$this->errorMessage = \view\Message::$wrongReleaseYear;
		} catch (\model\NoDescriptionException $e) {
			$this->errorMessage = \view\Message::$missingDescription;
		} catch (\model\InvalidFileTypeException $e) {
			$this->errorMessage = \view\Message::$invalidFileType;
		} catch (\model\ImageUploadException $e) {
			$this->errorMessage = \view\Message::$imageUploadError;
		} catch (\model\MissingRelaseYearException $e) {
			$this->errorMessage = \view\Message::$missingReleaseYear;
		} catch (\model\StringTooLongException $e) {
			$this->errorMessage = \view\Message::$stringIsTooLong;
		} catch (\model\InvalidFileSizeException $e) {
			$this->errorMessage = \view\Message::$fileSizeError;
		} catch (\model\InvalidCharException $e) {
			$this->errorMessage = \view\Message::$unallowedCharacters;
		} 

		// catch (\Exception $e) {
		// 	$this->errorMessage = \view\Message::$generalError;
		// } 
		
		return null;
	}

	/**
	 * Controller sets private member record to current record based on id in url.
	 * @param \model\Record $record record to update
	 */
	public function setRecord($record) {
		$this->record = $record;
	}

	/**
	 * @param string $message
	 */
	public function setErrorMessage($message) {
		$this->errorMessage = $message;
	}

	/**
	 * Same form is used for update, this method checks if record already exists. 
	 * @return boolean true if record exists
	 */
	private function isUpdate() {
		if ($this->record === null) {
			return false;
		} else {
			return true;
		}
	}
}