<?php
namespace HentTest;
use Hent\Node\Node;
use Hent\Router\BaseMySqlConfig;
use Hent\Router\MySqlConfig;
use Hent\Router\MySqlRouter;

class ExampleRouter extends MySqlRouter {

	/**
	 * @var Node
	 */
	public $exampleNode;

	public function __construct() {
		$this->exampleNode = parent::registerNode(new Node(new Example(new ExampleKey(null, null), null, null)));
		$config = BaseMySqlConfig::createFromJsonFile('../database.json');
//		$config = new ExampleRouterConfig();
		parent::__construct($config);
	}

}
class ExampleRouterConfig extends MySqlConfig {

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
		return 'hentTest';
	}

}
