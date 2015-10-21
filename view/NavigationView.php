<?php

namespace view;

class NavigationView {

	private static $newRecordURL = "nyskiva";
	private static $recordListURL = "skivlista";
	private static $recordShowURL = "record";
	private static $newRecordLinkClass = "not-active";
	private static $recordListLinkClass = "not-active";
	private static $homeLinkClass = "not-active";


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

	/**
	 * Provides record ID from the URL
	 * @return string recordID
	 */
	public function getRecordToShow() {
		return (int)$_GET[self::$recordShowURL];
	}

	private function setAsActive() {
		if($this->onNewRecordPage()) {
			self::$newRecordLinkClass = "active";
		} elseif ($this->onRecordListPage() || $this->onRecordShowPage()) {
			self::$recordListLinkClass = "active";
		} else {
			self::$homeLinkClass = "active";
		}
	}
}