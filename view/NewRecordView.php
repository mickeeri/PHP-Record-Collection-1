<?php

namespace view;

/**
* Class used for both registration of new record but also to update existing records.
*/
class NewRecordView {
		
	//private $errorMessageArray = array();
	private static $titleInputId = "title";
	private static $artistInputId = "artist";
	private static $releaseYearInputId = "year";
	private static $descriptionInputId = "description";
	private static $priceInputId = "price";
	private static $coverInputId = "cover";
	private static $submitPostId = "save";

	private $errorMessage = "";
	private $successMessage = "";
	public $isRecordSaved = false;
	//public $isRecordUpdated = false;

	private $record;

	function __construct() {

	}

	/**
	 * @return [type]
	 */
	public function response() {

		if ($this->isRecordSaved) {
			$this->successMessage = "Du har lagt till en ny skiva.";
		}

		$response = $this->getNewRecordForm();

		return $response;
	}

	/**
	 * Renders form for new record.
	 */
	private function getNewRecordForm() {

		if ($this->isUpdate()) {
			$header = "Uppdatera album";
		} else {
			$header = "Lägg till ny skiva";
		}

		return '
			<h2>'. $header . '</h2>
			' . $this->showErrorMessage() . '
			' . $this->showSuccessMessage() . '
			<form method="post" enctype="multipart/form-data">'.
				
				$this->getInputField("Titel", self::$titleInputId, "text") .
				$this->getInputField("Artist", self::$artistInputId, "text") .
				$this->getInputField("Utgivningsår", self::$releaseYearInputId, "text") .
				$this->getInputField("Om", self::$descriptionInputId, "textarea") .
				$this->getInputField("Pris", self::$priceInputId, "text") .
				$this->getInputField("Ladda upp omslag", self::$coverInputId, "file") 




				// <div class="form-group">
				// 	<label for="' . self::$descriptionInputId . '">Beskrivning</label>
				// 	<textarea rows="3" class="form-control" name ="' . self::$descriptionInputId . '" 
				// 	id="' . self::$descriptionInputId . '" placeholder="Beskrivning"></textarea>
				// </div>


				// <div class="form-group">
				// 	<label for="' . self::$coverInputId . '">Upload cover</label>
				// 	<input type="file" class="form-control" name ="' . self::$coverInputId . '" 
				// 	id="' . self::$coverInputId . '" value="Upload">
				// </div>
				.'<input class="btn btn-success" name="' . self::$submitPostId . '" type="submit" value="Spara">
			</form>
		';
	}

	/**
	 * Renders text fields for user input.
	 * @param  string $title input value
	 * @param  string $name  input name and id
	 */
	private function getInputField($title, $name, $type){

		// Get the value from current object if update otherwise textfields should be empty.
		if ($this->isUpdate()) {
			$value = $this->getPostField($name);
		} else {
			$value = "";
		}
		
		
		return "
			<div class='form-group'>
				<label for='$name'>$title</label>
				<input class='form-control' id='$name' type='$type' value='$value' name='$name' placeholder='$title'>
			</div>
			";
	}


	/**
	 * Get value of specific field.
	 * @param  string $field fields name
	 * @return string        field value
	 */
	private function getPostField($field){
		if ($this->isUpdate()) {
			
			if ($field === self::$titleInputId) {
				return trim($this->record->getTitle());
			} if ($field === self::$artistInputId) {
				return trim($this->record->getArtist());
			} if ($field === self::$releaseYearInputId) {
				return trim($this->record->getReleaseYear());
			} if ($field === self::$descriptionInputId) {
				return trim($this->record->getDescription());
			} if ($field === self::$priceInputId) {
				return trim($this->record->getPrice());
			}

			// if ($field === self::$coverInputId) {
			// 	return trim($this->record->getCoverFilePath());
			// }
		}




		// if (isset($_POST[$field])) {			
		// 	// Trims and removes special chars. 
		// 	return filter_var(trim($_POST[$field]), FILTER_SANITIZE_STRING);
		// }
		// return  "";
	}

	/**
	 * Returns div with error message if there is one.
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

	public function getNewRecord() {
		

		$title = $_POST[self::$titleInputId];
		$artist = $_POST[self::$artistInputId];
		$releaseYear = $_POST[self::$releaseYearInputId];
		$description = $_POST[self::$descriptionInputId];
		$price = $_POST[self::$priceInputId];
		
		if ($this->isUpdate()) {
			// TODO: Gör så att man kan byta bild.
			$cover = $this->record->getCoverFilePath();
			$recordID = $this->record->getRecordID();
		} else {
			$cover = $_FILES[self::$coverInputId];
			$recordID = null;
		}
				
		try {							
			
			$record = new \model\Record($title, $artist, $releaseYear, $description, $price, $cover);
			$record->setRecordID($recordID);

			return $record;

		} catch (\model\NoTitleException $e) {
			$this->errorMessage = "Title is missing";
		} catch (\model\NoArtistException $e) {
			$this->errorMessage = "Artist is missing.";
		} catch (\model\WrongReleaseYearException $e) {
			$this->errorMessage = "Releaseyear has to be in the format YYYY";
		} catch (\model\NoDescriptionException $e) {
			$this->errorMessage = "Description is missing.";
		} catch (\model\WrongPriceException $e) {
			$this->errorMessage = "The price is wrong.";
		} catch (\Exception $e) {
			$this->errorMessage = "Sorry! Something went wrong";
		} 
		
		return null;
	}

	/**
	 * Controller sets private member record to current record based on id in url.
	 * @param \model\Record $record record to update, null if new album 
	 */
	public function setRecord($record) {
		$this->record = $record;
	}

	public function setErrorMessage($message) {
		$this->errorMessage = $message;
	}

	private function isUpdate() {
		if ($this->record === null) {
			return false;
		} else {
			return true;
		}
	}
}