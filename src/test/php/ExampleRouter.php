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
	/**
	 * @var Node
	 */
	public $longKeyDatabean;

	public function __construct() {
		$this->exampleNode = parent::registerNode(new Node(Example::class, ExampleKey::class));
		$this->longKeyDatabean = parent::registerNode(new Node(LongKeyDatabean::class, LongKeyKey::class));
		$config = new ExampleRouterConfig();
		parent::__construct($config);
	}

}
