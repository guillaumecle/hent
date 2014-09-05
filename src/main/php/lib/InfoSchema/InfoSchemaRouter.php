<?php
require_once __DIR__.'/../router/Router.php';
require_once 'Tables.php';
require_once 'TablesKey.php';
class InfoSchemaRouter extends Router {

	/**
	 * @var Node
	 */
	public $tables;

	public function __construct() {
		$this->tables = parent::registerNode(new Node(new Tables(new TablesKey(null, null), null)));
		parent::__construct('information_schema');
	}

}