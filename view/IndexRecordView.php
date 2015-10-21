<?php

namespace view;

class IndexRecordView {

	private $records = array();
	
	public function __construct() {

	}

	public function response() {
		$response = $this->renderTable();

		return $response;
	}

	public function setListOfRecords($records) {
		$this->records = $records;
	}

	private function renderTable() {


		$ret = '
			<h2>Våra album</h2>
			<p>Klicka på album för att komma till köpsidan</p>
			<table class="table table-striped">
				<thead>
					<tr>
						<th>Titel</th>
						<th>Artist</th>
						<th>Utguvningsår</th>
						<th>Om</th>
						<th>Pris</th>
					</tr>
				</thead>
				<tbody>
					' . $this->renderTableRows() . '
				</tbody>
			</table>
		';

		return $ret;
	}

	private function renderTableRows() {
		$ret = '';

		foreach ($this->records as $record) {
			$ret .= '
				<tr>
					<td><a href="?record=' . $record->getRecordID() . '">' . $record->getTitle() . '</a></td>
					<td>' . $record->getArtist() . '</td>
					<td>' . $record->getReleaseYear() . '</td>
					<td>' . $record->getDescription() . '</td>
					<td>' . $record->getPrice() . ' SEK</td>
				</tr>
			';
		}

		return $ret;
	}
}