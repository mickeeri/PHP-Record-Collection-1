<?php

namespace view;

class NavigationView {

	private static $newRecordURL = "nyskiva";
	public static $recordListURL = "skivlista";
	private static $recordShowURL = "record";
	private static $deleteLinkURL = "deleterecord";

	// TODO: Make non static.
	private static $newRecordLinkClass = "not-active";
	private static $recordListLinkClass = "not-active";
	private static $homeLinkClass = "not-active";

	private static $sessionSaveLocation = "\\view\\NavigationView\\message";


	public function getNavigationBar() {
		$this->setAsActive();

		return '
		<ul class="nav nav-tabs">
			<li class="' . self::$homeLinkClass . '"><a href="?">Hem</a></li>
			<li class="' . self::$newRecordLinkClass . '"><a href="?' . self::$newRecordURL . '">Ny skiva</a></li>
			<li class="' . self::$recordListLinkClass . '"><a href="?' . self::$recordListURL . '">Se skivor</a></li>
		</ul>
		';
	}

	public function onNewRecordPage() {
		return isset($_GET[self::$newRecordURL]);
	}

	public function onRecordListPage() {
		return isset($_GET[self::$recordListURL]);
	}

	public function onRecordShowPage() {
		
		return isset($_GET[self::$recordShowURL]);
	}

	public function onDeleteRecordPage() {
		return isset($_GET[self::$deleteLinkURL]);
	}


	/**
	 * Provides record ID from the URL
	 * @return string recordID
	 */
	public function getRecordToShow() {
		if ($this->onRecordShowPage()) {
			return (int)$_GET[self::$recordShowURL];
		} elseif ($this->onDeleteRecordPage()) {
			return (int)$_GET[self::$deleteLinkURL];
		}
	}

	private function setAsActive() {
		if($this->onNewRecordPage()) {
			self::$newRecordLinkClass = "active";
		} elseif ($this->onRecordListPage() || $this->onRecordShowPage() || $this->onDeleteRecordPage()) {
			self::$recordListLinkClass = "active";
		} else {
			self::$homeLinkClass = "active";
		}
	}

	public function redirect($url, $message) {
		// if ($url === null) {
		// 	//$url = $_SERVER['PHP_SELF'];			
		// }
		// 
		$url = '/?'.$url;

		$_SESSION[self::$sessionSaveLocation] = $message;
		$actual_link = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['CONTEXT_PREFIX'].$url;
		header("Location: $actual_link");
		exit();
	}

	public function getHeaderMessage() {
		$message;

		if (isset($_SESSION[self::$sessionSaveLocation])) {
			$message = $_SESSION[self::$sessionSaveLocation];
			unset($_SESSION[self::$sessionSaveLocation]);
			
			return '
			<div class="alert alert-success" role="alert">
				' . $message . '
			</div>
			';	
		}		
	}
}