<?php
namespace Hent\Router;

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
	 * @return MySqlConfig
	 */
	public static function createFromJsonFile($filename) {
		$fileContent = file_get_contents($filename);
		$jsonContent = json_decode($fileContent, true);
		return new BaseMySqlConfig(
			$jsonContent['host'],
			$jsonContent['port'],
			$jsonContent['username'],
			$jsonContent['password'],
			$jsonContent['databaseName'],
			isset($jsonContent['attributes']) ? $jsonContent['attributes'] : null
		);
	}

}
