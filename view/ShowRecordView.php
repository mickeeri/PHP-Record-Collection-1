<?php

namespace view;

class ShowRecordView {

	private $record;
	
	function __construct() {
		# code...
	}

	public function response() {
		$response = $this->renderRecordInfo();

		return $response;
	}

	private function renderRecordInfo() {
		$ret = '
			<h2>' . $this->record->getTitle() . ' by ' . $this->record->getArtist() . '</h2>
			<ul>
				<p>Release year: ' . $this->record->getReleaseYear() . '</p>
				<p>About: ' . $this->record->getDescription() . '</p>
				<p>Price: ' . $this->record->getPrice() . ' $</p>
			</ul>
		';

		return $ret;
	}

	public function setRecord($record) {
		$this->record = $record;
	}
}