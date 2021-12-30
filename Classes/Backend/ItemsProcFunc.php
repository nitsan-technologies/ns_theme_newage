<?php
namespace NITSAN\NsThemeNewage\Backend;

class ItemsProcFunc {

	public function getIconsList(array &$config) {
		// change this to dynamically populate the list!
		$config['items'] = [
			// label, value
			['None', ''],
			['Camera', 'icon-camera'],
			['Lock Open', 'icon-lock-open'],
			['Present', 'icon-present'],
			['Smartphone', 'icon-screen-smartphone'],
		];
	}

	public function getFabIconsList(array &$config) {
		// change this to dynamically populate the list!
		$config['items'] = [
			// label, value
			['None', ''],
			['Twitter', 'fab fa-twitter'],
			['Facebook', 'fab fa-facebook-f'],
			['Instagram', 'fab fa-instagram'],
		];
	}

	public function getFaIconsList(array &$config) {
		// change this to dynamically populate the list!
		$config['items'] = [
			// label, value
			['None', ''],
			['Ad', 'fa-ad'],
			['Address-book', 'fa-address-book'],
			['Address-card', 'fa-address-card'],
			['Heart', 'fa-heart'],
		];
	}

}
