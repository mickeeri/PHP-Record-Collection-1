<?php

namespace view;


class LayoutView {

	/**
	 * Renders HTML basic layout. 
	 * @param  \view\NavigationView $nv 
	 * @param  \view\ $view provided by MasterController's generateOutput method. 
	 */
	public function render(\view\NavigationView $nv, $view) {
		echo '
		<!DOCTYPE html>
		    <html lang="en">
		      <head>
		        <meta charset="utf-8">
		        <meta http-equiv="X-UA-Compatible" content="IE=edge">
		        <meta name="viewport" content="width=device-width, initial-scale=1">
		        <link href="css/style.css" rel="stylesheet">
		        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">		        
		        <title>My Record Collection</title>
		  	</head>
	      	<body>				
				<div class="container">					
					<h1>My Record Collection</h1>
					' . $nv->getNavigationBar() . '
					<div class="content">
					' . $nv->getHeaderMessage() . '									       
		            ' . $view->response() . '
		            </div>
		        </div>
	       	</body>
	    </html>
	  	';
	}
}