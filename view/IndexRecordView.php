<?php

namespace view;

class IndexRecordView {

	private $records = array();
	
	// public function __construct() {

	// }

	public function response() {
		$response = $this->renderTable();

		return $response;
	}

	public function setListOfRecords($records) {
		$this->records = $records;
	}

	private function renderTable() {


		$ret = '
			<h2>My records</h2>
			<table class="table table-striped">
				<thead>
					<tr>
						<th>Title</th>
						<th>Artist</th>
						<th>Release year</th>
						<th>About</th>
						<th>My rating</th>
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
					<td><a href="?'. \view\NavigationView::$recordShowURL . '=' . $record->getRecordID() . '">' 
						. $record->getTitle() . '</a></td>
					<td>' . $record->getArtist() . '</td>
					<td>' . $record->getReleaseYear() . '</td>
					<td>' . $record->getDescription() . '</td>
					<td>' . $record->getRating() . '</td>
				</tr>
			';
		}

		return $ret;
	}
}