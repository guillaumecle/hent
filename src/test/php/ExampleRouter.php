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
		$this->exampleNode = parent::registerNode(new Node(Example::class, ExampleKey::class));
		$config = BaseMySqlConfig::createFromJsonFile('../database.json');
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
