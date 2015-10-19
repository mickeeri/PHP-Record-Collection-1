<?php

namespace view;

class LayoutView {
	public function render($view) {
		echo '
		<!DOCTYPE html lang="sv">
		    <html>
		      <head>
		        <meta charset="utf-8">
		        <meta http-equiv="X-UA-Compatible" content="IE=edge">
		        <meta name="viewport" content="width=device-width, initial-scale=1">
		        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
		        <title>Skivbörsen</title>
		  	</head>
	      	<body>
	        	<h1>Skivbörsen</h1>						        
		        <div class="container">
		            ' . $view->response() . '
		        </div>
	       	</body>
	    </html>
	  	';
	}
}