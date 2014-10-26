<?php
namespace HentTest;

use Hent\Router\MySqlConfig;

class ExampleRouterConfigExample extends MySqlConfig {

	/**
	 * @return string
	 */
	public function getUsername() {
		return 'root';
	}

	/**
	 * @return string
	 */
	public function getPassword() {
		return '';
	}

	/**
	 * @return string
	 */
	public function getDatabaseName() {
		return 'henttest';
	}

}
