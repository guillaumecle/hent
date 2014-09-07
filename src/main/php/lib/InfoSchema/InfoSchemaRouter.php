<?php
require_once __DIR__.'/../router/Router.php';
require_once 'Tables.php';
require_once 'TablesKey.php';
require_once 'Columns.php';
require_once 'ColumnsKey.php';
class InfoSchemaRouter extends Router {

	/**
	 * @var Node
	 */
	public $tables;

	/**
	 * @var Node
	 */
	public $columns;

	public function __construct() {
		$this->tables = parent::registerNode(new Node(new Tables(new TablesKey(null, null), null)));
		$this->columns = parent::registerNode(new Node(new Columns(new ColumnsKey(null, null))));
		parent::__construct('information_schema');
	}

}