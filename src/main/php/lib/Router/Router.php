<?php
namespace Hent\Router;
use Exception;
use Hent\Node\Node;
use Hent\SchemaUpdate\SchemaUpdater;
use PDO;

abstract class Router {

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
	 * @param $name String
	 * @param $updateSchema boolean
	 */
	public function __construct($name, $updateSchema = true) {
		$this->name = $name;
		if ($updateSchema) {
			$su = new SchemaUpdater($this);
			$su->updateSchema();
		}
		$this->connection = $this->createConnection();
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
	 * @return PDO
	 */
	private function createConnection() {
		$PARAM_host = 'localhost';
		$PARAM_port = '3306';
		$PARAM_user = 'root';
		$PARAM_mot_passe = '';
		$co = new PDO('mysql:host='.$PARAM_host.';port='.$PARAM_port.';dbname='.$this->getSqlName(), $PARAM_user, $PARAM_mot_passe);
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		return $co;
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
