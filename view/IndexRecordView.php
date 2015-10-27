<?php

namespace view;

/**
 * Handles list of all the records in database. 
 */
class IndexRecordView {

	private $records = array();

	public function response() {		
		return $this->renderTable();
	}

	/**
	 * RecordController provides array with the records. 
	 * @param array() $records all records in database. 
	 */
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