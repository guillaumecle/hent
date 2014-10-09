<?php
namespace Hent\Router;

use PDO;

abstract class MySqlConfig {

	/**
	 * @return string
	 */
	public function getHost() {
		return 'localhost';
	}

	/**
	 * @return int
	 */
	public function getPort() {
		return 3306;
	}

	/**
	 * @return string
	 */
	abstract public function getUsername();

	/**
	 * @return string
	 */
	abstract public function getPassword();

	/**
	 * @return string
	 */
	abstract public function getDatabaseName();

	/**
	 * @return array
	 */
	public function getAttributes() {
		$attributes = [];
		$attributes[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
		return $attributes;
	}

}
