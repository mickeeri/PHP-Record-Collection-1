<?php

namespace view;

class ShowRecordView {

	private $record;
	//private $recordRating = $record->getRating();

	private $userWantsToDeleteRecord = false;

	private static $deleteLinkID = "deleterecord";
	private static $confirmDeleteRecordID = "confirmdelete";
	private static $declineDeleteRecordID = "declinedelete";
	private static $updateLinkID = "uppdateraskiva";

	// Rating submit button names.
	private static $submitRating1ID = "rating1";
	private static $submitRating2ID = "rating2";
	private static $submitRating3ID = "rating3";
	private static $submitRating4ID = "rating4";
	private static $submitRating5ID = "rating5";

	// If album does not have rating the css class of the buttons is default.
	private $submitRating1CssClass = "default";
	private $submitRating2CssClass = "default";
	private $submitRating3CssClass = "default";
	private $submitRating4CssClass = "default";
	private $submitRating5CssClass = "default";


	//public $hasJustBeenRated = false;

	function __construct() {
		# code...
	}

	public function response() {

		// Check if record has rating.
		if ($this->record->getRating() !== null) {
			$this->displayCurrentRating();
		}
		
		// Check if user has pressed delete album link.
		if ($this->userWantsToDeleteRecord) {
			$response = $this->deleteRecordConfirmation();
		} else {
			// Just show record. 
			$response = $this->renderRecordInfo();
		}
	
		return $response;
	}

	/**
	 * Renders information and image of current album. 
	 * @return string HTML
	 */
	private function renderRecordInfo() {
		$imagePath = \Settings::PIC_UPLOAD_DIR . $this->record->getCoverFilePath();

		$ret = '
			<div class="media">
			  <div class="media-left">
			    <a href="#">
			      <img class="media-object" src="' . $imagePath . '" alt="Album cover art">
			    </a>
			  </div>
			  <div class="media-body">
			    <h3 class="media-heading">' . $this->record->getTitle() . ' by ' . $this->record->getArtist() . '</h3>
			    <p><strong>Release year:</strong> ' . $this->record->getReleaseYear() . '</p>
			    <p><strong>About:</strong> ' . $this->record->getDescription() . '</p>
			    <p><a href="?' . self::$deleteLinkID . '=' . $this->record->getRecordID() . '">Radera skiva</a></p>
			    <p><a href="?' . self::$updateLinkID . '=' . $this->record->getRecordID() . '">Redigera skiva</a></p>
				<h3>Rating</h3>
				<div class="rating">
					<form method="post">
						<input class="btn btn-' . $this->submitRating1CssClass .'" name="' . self::$submitRating1ID . '" type="submit" value="1">
						<input class="btn btn-' . $this->submitRating2CssClass .'" name="' . self::$submitRating2ID . '" type="submit" value="2">
						<input class="btn btn-' . $this->submitRating3CssClass .'" name="' . self::$submitRating3ID . '" type="submit" value="3">
						<input class="btn btn-' . $this->submitRating4CssClass .'" name="' . self::$submitRating4ID . '" type="submit" value="4">
						<input class="btn btn-' . $this->submitRating5CssClass .'" name="' . self::$submitRating5ID . '" type="submit" value="5">
					</form>
				</div>
			  </div>
			</div>
		';

		return $ret;
	}
	
	/**
	 * If record is rated, this method changes the css class of the button displaying 
	 * the current score. 
	 */
	private function displayCurrentRating() {
		
		if ($this->record->getRating() === 1) {
			$this->submitRating1CssClass = "primary";
		}

		if ($this->record->getRating() === 2) {
			$this->submitRating2CssClass = "primary";
		}

		if ($this->record->getRating() === 3) {
			$this->submitRating3CssClass = "primary";
		}

		if ($this->record->getRating() === 4) {
			$this->submitRating4CssClass = "primary";
		}

		if ($this->record->getRating() === 5) {
			$this->submitRating5CssClass = "primary";
		}
	}

	// private function buildRatingLinks() {
	// 	$ret = '';

	// 	// for ($i=1; $i <= 5; $i++) { 
	// 	// 	$ret .= '
	// 	// 		<a href="?' . \view\NavigationView::$recordShowURL . '=' . $this->record->getRecordID() . '?' . 
	// 	// 			\view\NavigationView::$ratingLinkID . '=' . $i . '">' . $i .'</a>
	// 	// 	';
	// 	// }
	// 	// 
	// 	for ($i=1; $i <= 5; $i++) { 
	// 		$ret .= '
	// 			<a href="?' . \view\NavigationView::$ratingLinkID . '=' . $i . '">' . $i .'</a>
	// 		';
	// 	}

	// 	return $ret;
	// }


	/**
	 * If user has pressed delete record link this confirmation form is rendered.
	 */
	private function deleteRecordConfirmation() {
		$ret = '
			<div class="alert alert-warning" role="alert">
				<p>Är du säker på att du vill ta bort <strong>' . $this->record->getTitle() . ' av ' . 
					$this->record->getArtist() . '</strong></p>
			</div>
			<form method="post">
				<input class="btn btn-danger" name="' . self::$confirmDeleteRecordID . '" type="submit" value="Ja">
				<input class="btn btn-default" name="' . self::$declineDeleteRecordID . '" type="submit" value="Nej">
			</form>
		';

		return $ret;
	}

	/**
	 * Controller sets the choosen record.
	 * @param \model\Record $record
	 */
	public function setRecord($record) {
		$this->record = $record;
	}

	/**
	 * Sets the records current rating.
	 * @param int $rating
	 */
	public function setRecordRating($rating) {
		$this->recordRating = $rating;
	}

	/**
	 * Sets the private boolean member userWantsToDeleteRecord.
	 */
	public function setUserWantsToDeleteRecord() {
		$this->userWantsToDeleteRecord = true;
	}

	/**
	 * @return boolean true if user has confirmed deletion of record.
	 */
	public function userHasConfirmedDelete() {
		return isset($_POST[self::$confirmDeleteRecordID]);
	}

	/**
	 * @return boolean true if user has answered no on question "Do you want to delete album..."
	 */
	public function userHasDeclinedDelete() {		
		return isset($_POST[self::$declineDeleteRecordID]);
	}	

	/**
	 * Returns value of rating if one of the ratings is set.
	 * @return int score, null if no rating is posted.
	 */
	public function getSubmittedRecordRating() {

		if (isset($_POST[self::$submitRating1ID])) {
			
			return (int)$_POST[self::$submitRating1ID];

		} elseif (isset($_POST[self::$submitRating2ID])) {
			
			return (int)$_POST[self::$submitRating2ID];

		} elseif (isset($_POST[self::$submitRating3ID])) {
			
			return (int)$_POST[self::$submitRating3ID];

		} elseif (isset($_POST[self::$submitRating4ID])) {
			
			return (int)$_POST[self::$submitRating4ID];

		} elseif (isset($_POST[self::$submitRating5ID])) {
			
			return (int)$_POST[self::$submitRating5ID];

		} else {
			return null;
		}
	}
}