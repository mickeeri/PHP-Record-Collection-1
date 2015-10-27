<?php

require_once("Settings.php");
require_once("view/LayoutView.php");
require_once("view/NavigationView.php");
require_once("controller/MasterController.php");
require_once("view/MessageView.php");

if (Settings::DISPLAY_ERRORS) {
	error_reporting(-1);
	ini_set('display_errors', 'ON');
}

session_start();

$nv = new \view\NavigationView();
$mc = new \controller\MasterController($nv);

$mc->handleInput();

// Deside which view is to be rendered based on handleInput();
$view = $mc->generateOutput();

$lv = new \view\LayoutView();

$lv->render($nv, $view);

