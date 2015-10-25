<?php

namespace view;

/**
* 
*/
class UpdateRecordView {
	
	function __construct() {
		# code...
	}

	public function response() {
		$this->getUpdateRecordForm();
	}


	private function getUpdateRecordForm() {
		private function getNewRecordForm() {
			return '
				<h2>Uppdatera album</h2>
				' . $this->showErrorMessage() . '
				' . $this->showSuccessMessage() . '
				<form method="post" enctype="multipart/form-data">
					<div class="form-group">
						<label for="' . self::$titleInputId . '">Titel</label>
						<input type="text" class="form-control" name ="' . self::$titleInputId . '" id="' . self::$titleInputId . '" placeholder="Titel" value="Tralala">
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

					<div class="form-group">
						<label for="' . self::$coverInputId . '">Upload cover</label>
						<input type="file" class="form-control" name ="' . self::$coverInputId . '" id="' . self::$coverInputId . '" value="Upload">
					</div>
					<input class="btn btn-success" name="' . self::$submitPostId . '" type="submit" value="Spara">
				</form>
			';
		}
	}
}