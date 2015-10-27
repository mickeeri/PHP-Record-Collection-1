<?php

namespace view;

/**
* Application start page view.
*/
class HomeView {
	
	/**
	 * Array with latest four records. 
	 * @var array
	 */
	private $latestRecords = array();

	public function response() {
		return $this->getHTML();
	}

	private function getHTML() {
		return '
			<h2>Latest records</h2>
			<div class="row">
				' . $this->getThumbnails() . '
			</div>
		';
	}

	/**
	 * Renders all the records in the array as thumbnails. 
	 * @return string HTML
	 */
	private function getThumbnails() {
		$ret = '';

		foreach ($this->latestRecords as $record) {
			
			$queryString = \view\NavigationView::$recordShowURL . "=" . $record->getRecordID();

			$ret .= '
				<div class="col-sm-6 col-md-3">
					<div class="thumbnail">
						<img src="' . \Settings::PIC_UPLOAD_DIR.$record->getCoverFilePath() . '" alt="Cover art">
						<div class="caption">
							<h3>' . $record->getTitle() . '</h3>
							<h4>' . $record->getArtist() . '</h4>
							<p>' . $record->getDescription() . '</p>						
							<a href="?'. $queryString . '" class="btn btn-danger" role="button">More info</a>			
						</div>
					</div>
				</div>
			';
		}

		return $ret;
	}

	/**
	 * Recordcontroller provides latest records from RecordFacade. 
	 * @param array() $recordArray
	 */
	public function setLatestRecords($recordArray) {
		$this->latestRecords = $recordArray;
	}
}

