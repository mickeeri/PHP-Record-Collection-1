<?php

namespace view;

/**
* Application start page view.
*/
class HomeView {
	
	private $latestRecords = array();

	function __construct() {
		
	}

	public function response() {
		return $this->getHTML();
	}

	private function getHTML() {
		return '
			<h2>Senaste skivorna</h2>
			<div class="row">
				' . $this->getThumbnails() . '
			</div>
		';
	}

	private function getThumbnails() {
		$ret = '';

		foreach ($this->latestRecords as $record) {
			$ret .= '
				<div class="col-sm-6 col-md-3">
					<div class="thumbnail">
						<img src="' . \Settings::PIC_UPLOAD_DIR.$record->getCoverFilePath() . '" alt="Omslagsbild">
						<div class="caption">
							<h3>' . $record->getTitle() . '</h3>
							<h4>' . $record->getArtist() . '</h4>
							<p>' . $record->getDescription() . '</p>
							

							<a href="?'. \view\NavigationView::$recordShowURL . '=' . $record->getRecordID() . '"" 
							class="btn btn-default" role="button">Mer info</a>
							<a href="#" class="btn btn-danger" role="button">Köp</a>

							</p>				
						</div>
					</div>
				</div>
			';
		}

		return $ret;
	}

	public function setLatestRecords($recordArray) {
		$this->latestRecords = $recordArray;
	}
}

