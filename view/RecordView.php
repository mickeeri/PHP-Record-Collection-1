<?php

namespace view;

/**
* 
*/
class RecordView {
	
	
	//private $errorMessageArray = array();
	private static $titleInputId = "title";
	private static $artistInputId = "artist";
	private static $releaseYearInputId = "year";
	private static $descriptionInputId = "description";
	private static $priceInputId = "price";
	private static $submitPostId = "save";

	private $errorMessage = "";
	private $successMessage = "";
	public $isRecordSaved = false;

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
		return '
			<h2>Lägg till ny skiva</h2>
			' . $this->showErrorMessage() . '
			' . $this->showSuccessMessage() . '
			<form method="post">
				<div class="form-group">
					<label for="' . self::$titleInputId . '">Titel</label>
					<input type="text" class="form-control" name ="' . self::$titleInputId . '" id="' . self::$titleInputId . '" placeholder="Titel">
				</div>
				<div class="form-group">
					<label for="' . self::$artistInputId . '">Artist</label>
					<input type="text" class="form-control" name ="' . self::$artistInputId . '" id="' . self::$artistInputId . '" placeholder="Artist">
				</div>

				<div class="form-group">
					<label for="' . self::$releaseYearInputId . '">Realseår</label>
					<input type="text" class="form-control" name ="' . self::$releaseYearInputId . '" id="' . self::$releaseYearInputId . '" placeholder="Releaseår">
				</div>

				<div class="form-group">
					<label for="' . self::$descriptionInputId . '">Beskrivning</label>
					<textarea rows="3" class="form-control" name ="' . self::$descriptionInputId . '" id="' . self::$descriptionInputId . '" placeholder="Beskrivning"></textarea>
				</div>

				<div class="form-group">
					<label for="' . self::$priceInputId . '">Pris</label>
					<input type="text" class="form-control" name ="' . self::$priceInputId . '" id="' . self::$priceInputId . '" placeholder="Pris">
				</div>
				<input class="btn btn-success" name="' . self::$submitPostId . '" type="submit" value="Spara">
			</form>
		';
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
	public function userWantsToAddRecord() {
		return isset($_POST[self::$submitPostId]);
	}

	public function getNewRecord() {
		
		$title = $_POST[self::$titleInputId];
		$artist = $_POST[self::$artistInputId];
		$releaseYear = $_POST[self::$releaseYearInputId];
		$description = $_POST[self::$descriptionInputId];
		$price = $_POST[self::$priceInputId];

		try {
			return new \model\Record($title, $artist, $releaseYear, $description, $price);
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
}