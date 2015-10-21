<?php

namespace view;

/**
* Application start page view.
*/
class HomeView {
	
	function __construct() {
		
	}

	public function response() {
		return $this->getHTML();
	}

	private function getHTML() {
		



		return "
			<h4>Välkommen till startsidan</h4>	
		";
	}
}