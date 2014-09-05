<?php
require_once __DIR__ . '/../util.php';
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
	 */
	public function __construct($name) {
		$this->name = $name;
		$PARAM_host = 'localhost';
		$PARAM_port = '3306';
		$PARAM_nom_bd = $this->name;
		$PARAM_user = 'root';
		$PARAM_mot_passe = '';
		$co = new PDO('mysql:host='.$PARAM_host.';port='.$PARAM_port.';dbname='.$PARAM_nom_bd, $PARAM_user, $PARAM_mot_passe);
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$this->connection = $co;
		foreach ($this->nodes as $node) {
			$node->setConnection($co);
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
	private function getConnection() {
		return $this->connection;
	}

	/**
	 * @return String
	 */
	public function getName() {
		return $this->name;
	}

	private function doSchemaUpdate() {
		$tablesQuery = $this->connection->prepare('select * from information_schema.tables where table_schema = \'' . $this->getName() . '\'');
		$tablesQuery->execute();
		$tables = [];
		while ($table = $tablesQuery->fetch()) {
			$tables[] = Node::fromInformationSchema($table);
		}
		foreach ($this->nodes as $node) {
			if (in_array($node->getSqlName(), $tables)) {
				$describeQuery = $this->connection->prepare('describe ' . $node->getSqlName());
				$describeQuery->execute();
				while ($describe = $describeQuery->fetch()) {
					$describe = BaseField::fromDescribe($describe);
				}
			} else {
				//$this->doCreate($node);
			}
		}
	}

	/**
	 * @param $node Node
	 */
	public function doCreate($node) {
		println('Create Table:');
		println($node->getCreateScript());
		$this->connection->prepare($node->getCreateScript())->execute();
	}

}
