<?php
namespace HentTest;
use Hent\Node\Node;
use Hent\Router\Router;

class MyRouter extends Router {

	/**
	 * @var Node
	 */
	public $exampleNode;

	public function __construct() {
		$this->exampleNode = parent::registerNode(new Node(new Example(new ExampleKey(null, null), null, null)));
		parent::__construct('hentTest');
	}

}
