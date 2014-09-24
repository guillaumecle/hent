<?php
require_once __DIR__ . '/../util.php';
require_once __DIR__ . '/../SchemaUpdate/SchemaUpdater.php';
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
		$PARAM_host = 'localhost';
		$PARAM_port = '3306';
		$PARAM_nom_bd = $this->name;
		$PARAM_user = 'root';
		$PARAM_mot_passe = '';
		$co = new PDO('mysql:host='.$PARAM_host.';port='.$PARAM_port.';dbname='.$PARAM_nom_bd, $PARAM_user, $PARAM_mot_passe);
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$this->connection = $co;
		if (!isset($this->nodes)) {
			$this->nodes = [];
		}
		foreach ($this->nodes as $node) {
			$node->setConnection($co);
		}
		if ($updateSchema) {
			$su = new SchemaUpdater($this);
			$su->updateSchema();
		}
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
		return strtolower($this->name);
	}

	/**
	 * @return Node[]
	 */
	public function getNodes() {
		return $this->nodes;
	}

}
