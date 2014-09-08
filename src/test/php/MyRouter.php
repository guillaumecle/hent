<?php
require_once __DIR__.'/../../main/php/lib/router/Router.php';
class MyRouter extends Router {

	/**
	 * @var Node
	 */
	public $exampleNode;

	public function __construct() {
		$this->exampleNode = parent::registerNode(new Node(new Example(new ExampleKey(null), null)));
		parent::__construct('datamanagertest');
	}

}
