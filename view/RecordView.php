<?php

namespace view;

/**
* 
*/
class RecordView {
	
	private static $errorMessage = "";
	private static $titleInputId = "title";
	private static $artistInputId = "artist";
	private static $releaseYearInputId = "year";
	private static $descriptionInputId = "description";
	private static $submitPostId = "save";

	function __construct() {

	}

	/**
	 * @return [type]
	 */
	public function response() {
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
			<form method="post">
				<div class="form-group">
					<label for="' . self::$titleInputId . '">Titel</label>
					<input type="text" class="form-control" id="' . self::$titleInputId . '" placeholder="Titel">
				</div>
				<div class="form-group">
					<label for="' . self::$artistInputId . '">Artist</label>
					<input type="text" class="form-control" id="' . self::$artistInputId . '" placeholder="Artist">
				</div>

				<div class="form-group">
					<label for="' . self::$releaseYearInputId . '">Realseår</label>
					<input type="text" class="form-control" id="' . self::$releaseYearInputId . '" placeholder="Releaseår">
				</div>

				<div class="form-group">
					<label for="' . self::$descriptionInputId . '">Beskrivning</label>
					<textarea rows="3" class="form-control" id="' . self::$descriptionInputId . '" placeholder="Beskrivning"></textarea>
				</div>
				<input class="btn btn-success" name="' . self::$submitPostId . '" type="submit" value="Spara">
			</form>
		';
	}

	/**
	 * Returns div with error message if there is one.
	 */
	private function showErrorMessage() {
		if (self::$errorMessage === "") {
			return false;
		}

		return '
			<div class="alert alert-danger" role="alert">
				<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
				<span class="sr-only">Error:</span>
				' . self::$errorMessage . '
			</div>
		';
	}
}