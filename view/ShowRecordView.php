<?php

namespace view;

class ShowRecordView {

	private $record;
	private $recordRating;

	private $userWantsToDeleteRecord = false;

	private static $deleteLinkID = "deleterecord";
	private static $confirmDeleteRecordID = "confirmdelete";
	private static $declineDeleteRecordID = "declinedelete";
	private static $updateLinkID = "uppdateraskiva";

	// Rating input button names.
	private static $submitRating1ID = "rating1";
	private static $submitRating2ID = "rating2";
	private static $submitRating3ID = "rating3";
	private static $submitRating4ID = "rating4";
	private static $submitRating5ID = "rating5";

	private $submitRating1CssClass = "default";
	private $submitRating2CssClass = "default";
	private $submitRating3CssClass = "default";
	private $submitRating4CssClass = "default";
	private $submitRating5CssClass = "default";


	public $hasJustBeenRated = false;

	function __construct() {
		# code...
	}

	public function response() {

		// Check if record has rating.
		if ($this->recordRating !== null) {
			$this->displayCurrentRating();
		}
		
		if ($this->userWantsToDeleteRecord) {
			$response = $this->deleteRecordConfirmation();
		} else {
			$response = $this->renderRecordInfo();
		}
	
		return $response;
	}

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
			    <p>Release year: ' . $this->record->getReleaseYear() . '</p>
			    <p>About: ' . $this->record->getDescription() . '</p>
			    <p>Price: ' . $this->record->getPrice() . ' $</p>
			    <p><a href="?' . self::$deleteLinkID . '=' . $this->record->getRecordID() . '">Radera skiva</a></p>
			    <p><a href="?' . self::$updateLinkID . '=' . $this->record->getRecordID() . '">Redigera skiva</a></p>
				<h3>Betyg</h3>
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
		if ($this->recordRating === 1) {
			$this->submitRating1CssClass = "primary";
		}

		if ($this->recordRating === 2) {
			$this->submitRating2CssClass = "primary";
		}

		if ($this->recordRating === 3) {
			$this->submitRating3CssClass = "primary";
		}

		if ($this->recordRating === 4) {
			$this->submitRating4CssClass = "primary";
		}

		if ($this->recordRating === 5) {
			$this->submitRating5CssClass = "primary";
		}
	}



	private function buildRatingLinks() {
		$ret = '';

		// for ($i=1; $i <= 5; $i++) { 
		// 	$ret .= '
		// 		<a href="?' . \view\NavigationView::$recordShowURL . '=' . $this->record->getRecordID() . '?' . 
		// 			\view\NavigationView::$ratingLinkID . '=' . $i . '">' . $i .'</a>
		// 	';
		// }
		// 
		for ($i=1; $i <= 5; $i++) { 
			$ret .= '
				<a href="?' . \view\NavigationView::$ratingLinkID . '=' . $i . '">' . $i .'</a>
			';
		}

		return $ret;
	}



	private function deleteRecordConfirmation() {
		$ret = '
			<div class="alert alert-warning" role="alert">
				<p>Är du säker på att du vill ta bort <strong>' . $this->record->getTitle() . ' av ' . $this->record->getArtist() . '</strong></p>
			</div>
			<form method="post">
				<input class="btn btn-danger" name="' . self::$confirmDeleteRecordID . '" type="submit" value="Ja">
				<input class="btn btn-default" name="' . self::$declineDeleteRecordID . '" type="submit" value="Nej">
			</form>
		';

		return $ret;
	}

	public function setRecord($record) {
		$this->record = $record;
	}

	public function setRecordRating($rating) {
		$this->recordRating = $rating;
	}

	public function setUserWantsToDeleteRecord() {
		$this->userWantsToDeleteRecord = true;
	}

	public function userHasConfirmedDelete() {
		return isset($_POST[self::$confirmDeleteRecordID]);
	}

	// If user has answered no on question "Do you want to delete album..."
	public function userHasDeclinedDelete() {		
		return isset($_POST[self::$declineDeleteRecordID]);
	}	

	/**
	 * Returns value of rating if one of the ratings is set.
	 * @return int score, null if no rating is given.
	 */
	public function getSubmittedRecordRating() {
		if (isset($_POST[self::$submitRating1ID])) {
			
		} elseif (isset($_POST[self::$submitRating1ID])) {
			
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