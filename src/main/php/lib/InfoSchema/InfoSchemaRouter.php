<?php
require_once __DIR__.'/../router/Router.php';
require_once 'Tables.php';
require_once 'Columns.php';
require_once 'KeyColumnUsage.php';
class InfoSchemaRouter extends Router {

	/**
	 * @var Node
	 */
	public $tables;

	/**
	 * @var Node
	 */
	public $columns;

	/**
	 * @var Node
	 */
	public $keys;

	public function __construct() {
		$this->tables = parent::registerNode(new Node(new Tables()));
		$this->columns = parent::registerNode(new Node(new Columns()));
		$this->keys = parent::registerNode((new Node(new KeyColumnUsage(), 'KEY_COLUMN_USAGE')));
		parent::__construct('information_schema', false);
	}

}
