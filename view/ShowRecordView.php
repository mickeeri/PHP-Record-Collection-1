<?php

namespace view;

class ShowRecordView {

	private $record;

	private $userWantsToDeleteRecord = false;

	private static $deleteLinkID = "deleterecord";
	private static $confirmDeleteRecordID = "confirmdelete";
	private static $declineDeleteRecordID = "declinedelete";
	private static $updateLinkID = "uppdateraskiva";

	function __construct() {
		# code...
	}

	public function response() {

		var_dump($this->userHasDeclinedDelete());

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
			    <a href="?' . self::$deleteLinkID . '=' . $this->record->getRecordID() . '">Radera skiva</a>
			    <a href="?' . self::$updateLinkID . '=' . $this->record->getRecordID() . '">Redigera skiva</a>
			  </div>
			</div>
		';

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
}