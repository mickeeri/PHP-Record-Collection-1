<?php

namespace view;

/**
* Application start page view.
*/
class HomeView {
	
	function __construct() {
		
	}

	public function response() {
		return "
			<h4>Välkommen till startsidan</h4>	
		";
	}
}