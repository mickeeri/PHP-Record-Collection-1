<?php

namespace view;

class NavigationView {

	private static $newRecordURL = "nyskiva";
	public static $recordListURL = "skivlista";
	public static $recordShowURL = "record";
	private static $deleteLinkURL = "deleterecord";
	private static $updateLinkURL = "uppdateraskiva";


	// Public static links
	//public static $orderLinkID = "order";
	public static $ratingLinkID = "rate";

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

	public function onUpdateRecordPage() {
		return isset($_GET[self::$updateLinkURL]);
	}

	// public function onOrderPage() {
	// 	return isset($_GET[self::$orderLinkID]);
	// }

	public function wantsToRateRecord() {		
		return isset($_GET[self::$ratingLinkID]);
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
		} elseif ($this->onUpdateRecordPage()) {
			return (int)$_GET[self::$updateLinkURL];
		} elseif ($this->onOrderPage()) {
			return (int)$_GET[self::$orderLinkID];
		}
	}

	public function getRecordRating() {
		return (int)$_GET[self::$ratingLinkID];
	}

	public function getCurrentRecordIDFromCookie() {
		
		if (isset($_COOKIE["currentrecord"])) {
			$ret = $_COOKIE["currentrecord"];
		} else {
			$ret = "";
		}

		// Removes cookie.
		setcookie("currentrecord", "", time() - 1);

		return (int)$ret;
	}

	private function setAsActive() {
		if($this->onNewRecordPage()) {
			self::$newRecordLinkClass = "active";
		} elseif ($this->onRecordListPage() || $this->onRecordShowPage() || $this->onDeleteRecordPage() || $this->onUpdateRecordPage()) {
			self::$recordListLinkClass = "active";
		} else {
			self::$homeLinkClass = "active";
		}
	}

	/**
	 * Redirects to specific url.
	 * @param  string $url     
	 * @param  string $message message to be displayed after redirect
	 */
	public function redirect($url, $message) {
		// if ($url === null) {
		// 	//$url = $_SERVER['PHP_SELF'];			
		// }
		// 
		// 
		
		if ($url != "") {
			$url = '?'.$url;
		}
	
		// Removes index.php from php_self string.
		$path = str_replace("index.php", "", $_SERVER['PHP_SELF']);
		
		$_SESSION[self::$sessionSaveLocation] = $message;
		$actual_link = 'http://'.$_SERVER['HTTP_HOST'].$path.$url;
		header("Location: $actual_link");
		exit();
	}

	/**
	 * Reloads current page.
	 * @return void
	 */
	public function refresh() {
		header('Location: '.$_SERVER['REQUEST_URI']);
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