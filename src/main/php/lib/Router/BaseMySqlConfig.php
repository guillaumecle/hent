<?php
namespace Hent\Router;

use Exception;

class BaseMySqlConfig extends MySqlConfig {

	/**
	 * @var string
	 */
	private $host;

	/**
	 * @var int
	 */
	private $port;

	/**
	 * @var string
	 */
	private $username;

	/**
	 * @var string
	 */
	private $password;

	/**
	 * @var string
	 */
	private $databaseName;

	/**
	 * @var array
	 */
	private $attributes;

	/**
	 * @param $host
	 * @param $port
	 * @param $username
	 * @param $password
	 * @param $databaseName
	 * @param array $attributes
	 */
	function __construct($host, $port, $username, $password, $databaseName, $attributes = null) {
		$this->host = $host;
		$this->port = $port;
		$this->username = $username;
		$this->password = $password;
		$this->databaseName = $databaseName;
		$this->attributes = $attributes;
	}

	/**
	 * @return string
	 */
	public function getHost() {
		return $this->host;
	}

	/**
	 * @return int
	 */
	public function getPort() {
		return $this->port;
	}

	/**
	 * @return string
	 */
	public function getUsername() {
		return $this->username;
	}

	/**
	 * @return string
	 */
	public function getPassword() {
		return $this->password;
	}

	/**
	 * @return string
	 */
	public function getDatabaseName() {
		return $this->databaseName;
	}

	public function getAttributes() {
		if ($this->attributes == null) {
			return parent::getAttributes();
		}
		return $this->attributes;
	}

	/**
	 * @param string $filename
	 * @throws Exception
	 * @return MySqlConfig
	 */
	public static function createFromJsonFile($filename) {
		$fileContent = file_get_contents($filename);
		if ($fileContent === false) {
			throw new Exception('Can not find database configuration json file : ' . $filename);
		}
		$jsonContent = json_decode($fileContent, true);
		if ($jsonContent === null) {
			throw new Exception('Can not decode database configuration json file : ' . $filename);
		}
		$requiredParams = ['username', 'password', 'name'];
		foreach ($requiredParams as $requiredParam) {
			if (!isset($jsonContent[$requiredParam])) {
				throw new Exception('Missing "' . $requiredParam  . '" in database configuration json file ' . $filename);
			}
		}

		return new BaseMySqlConfig(
			isset($jsonContent['host']) ? $jsonContent['host'] : null,
			isset($jsonContent['port']) ? $jsonContent['port'] : null,
			$jsonContent['username'],
			$jsonContent['password'],
			$jsonContent['name'],
			isset($jsonContent['attributes']) ? $jsonContent['attributes'] : null
		);
	}

}
