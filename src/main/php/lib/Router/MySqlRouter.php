<?php
namespace Hent\Router;
use Exception;
use Hent\Node\Node;
use Hent\SchemaUpdate\SchemaUpdater;
use PDO;

abstract class MySqlRouter implements Router {

	/**
	 * @var PDO
	 */
	private $connection;

	/**
	 * @var String
	 */
	private $name;

	/**
	 * @var Node[]
	 */
	private $nodes;

	/**
	 * @param MySqlConfig $config
	 * @param boolean $updateSchema
	 */
	public function __construct(MySqlConfig $config, $updateSchema = true) {
		$this->name = $config->getDatabaseName();
		if ($updateSchema) {
			$su = new SchemaUpdater($config, $this);
			$su->updateSchema();
		}
		$this->connection = $this->createConnection($config);
		$this->updateNodes();
	}

	/**
	 * @param $node Node
	 * @return Node
	 */
	protected function registerNode($node) {
		$this->nodes[] = $node;
		return $node;
	}

	/**
	 * @return PDO
	 */
	public function getConnection() {
		return $this->connection;
	}

	/**
	 * @return String
	 */
	private function getName() {
		return $this->name;
	}

	/**
	 * @return String
	 */
	public function getSqlName() {
		return strtolower($this->getName());
	}

	/**
	 * @return Node[]
	 */
	public function getNodes() {
		return $this->nodes;
	}

	/**
	 * @param MySqlConfig $config
	 * @return PDO
	 */
	private function createConnection(MySqlConfig $config) {
		$host = $config->getHost();
		$port = $config->getPort();
		$username = $config->getUsername();
		$password = $config->getPassword();
		$connection = new PDO('mysql:host=' . $host . ';port=' . $port . ';dbname='.$this->getSqlName(), $username, $password);
		foreach ($config->getAttributes() as $attribute => $value) {
			var_dump($attribute, $value);
			$connection->setAttribute($attribute, $value);
		}
		return $connection;
	}

	private function updateNodes() {
		if (empty($this->connection)) {
			throw new Exception('Router::connection should be set before calling this');
		}
		if (!isset($this->nodes)) {
			$this->nodes = [];
		}
		foreach ($this->nodes as $node) {
			$node->setConnection($this->connection);
		}
	}

}
