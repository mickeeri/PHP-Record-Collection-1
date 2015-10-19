<?php

require_once("Settings.php");
require_once("view/LayoutView.php");
require_once("controller/MasterController.php");

if (Settings::DISPLAY_ERRORS) {
	error_reporting(-1);
	ini_set('display_errors', 'ON');
}

session_start();

$mc = new \controller\MasterController();

$mc->handleInput();

// Deside which view is to be rendered based on handleInput();
$view = $mc->generateOutput();

$lv = new \view\LayoutView();

$lv->render($view);

