<?php

namespace view;

class NavigationView {

	// Static url's to use GET on. Some of them public to be accessed in other views. 
	private static $newRecordURL = "newrecord";
	public static $recordListURL = "records";
	public static $recordShowURL = "record";
	private static $deleteLinkURL = "deleterecord";
	public static $updateLinkURL = "editrecord";

	//public static $ratingLinkID = "rate";

	// Navbar css classes can be displayed as "not-active" or "active"
	private $newRecordLinkClass = "not-active";
	private $recordListLinkClass = "not-active";
	private $homeLinkClass = "not-active";

	private static $sessionSaveLocation = "\\view\\NavigationView\\message";

	private static $currentRecordCookieName = "currentrecord";


	public function getNavigationBar() {
		$this->setAsActive();

		return '
		<ul class="nav nav-tabs">
			<li class="' . $this->homeLinkClass . '"><a href="?">Home</a></li>
			<li class="' . $this->newRecordLinkClass . '"><a href="?' . self::$newRecordURL . '">Add new record</a></li>
			<li class="' . $this->recordListLinkClass . '"><a href="?' . self::$recordListURL . '">List of records</a></li>
		</ul>
		';
	}

	
	/**
	 * Booelean methods returning if on certain page. 
	 */
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

	public function onUpdateRecordPage() {
		return isset($_GET[self::$updateLinkURL]);
	}

	// public function wantsToRateRecord() {		
	// 	return isset($_GET[self::$ratingLinkID]);
	// }


	/**
	 * Provides record ID from the URL
	 * @return string recordID
	 */
	public function getRecordToShow() {
		
		if ($this->onRecordShowPage()) {
			return (int)$_GET[self::$recordShowURL];
		} elseif ($this->onDeleteRecordPage()) {
			return (int)$_GET[self::$deleteLinkURL];
		} elseif ($this->onUpdateRecordPage()) {
			return (int)$_GET[self::$updateLinkURL];
		} elseif ($this->onOrderPage()) {
			return (int)$_GET[self::$orderLinkID];
		}
	}


	public function getCurrentRecordIDFromCookie() {		
		
		if (isset($_COOKIE[self::$currentRecordCookieName])) {
			$ret = $_COOKIE[self::$currentRecordCookieName];
		} else {
			$ret = "";
		}

		// Removes cookie.
		setcookie(self::$currentRecordCookieName, "", time() - 1);

		return (int)$ret;
	}

	private function setAsActive() {
		if($this->onNewRecordPage() || $this->onUpdateRecordPage()) {
			$this->newRecordLinkClass = "active";
		} elseif ($this->onRecordListPage() || $this->onRecordShowPage() || $this->onDeleteRecordPage()) {
			$this->recordListLinkClass = "active";
		} else {
			$this->homeLinkClass = "active";
		}
	}

	/**
	 * Redirects to specific url and saves message in session to be displayed after redirect. 
	 * @param  string $queryString  
	 * @param  string $message message to be displayed after redirect
	 */
	public function redirect($queryString, $message) {
		
		if ($queryString != "") {
			$queryString = '?'.$queryString;
		}
	
		// Removes index.php from php_self string.
		$path = str_replace("index.php", "", $_SERVER['PHP_SELF']);
		
		$_SESSION[self::$sessionSaveLocation] = $message;
		$actual_link = 'http://'.$_SERVER['HTTP_HOST'].$path.$queryString;
		header("Location: $actual_link");
		exit();
	}

	/**
	 * Simple reload of current page. 
	 * @return void
	 */
	public function refresh() {
		header('Location: '.$_SERVER['REQUEST_URI']);
	}

	/**
	 * Get message stored in session if it is set. 
	 * @return HTML div with message if session is set. 
	 */
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