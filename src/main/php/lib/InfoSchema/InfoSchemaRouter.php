<?php
require_once __DIR__.'/../router/Router.php';
require_once 'Schemata.php';
require_once 'Tables.php';
require_once 'Columns.php';
require_once 'KeyColumnUsage.php';
require_once 'Indexes.php';
class InfoSchemaRouter extends Router {

	/**
	 * @var Node
	 */
	public $schemata;

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

	/**
	 * @var Node
	 */
	public $indexes;

	public function __construct() {
		$this->schemata = parent::registerNode(new Node(new Schemata(), 'SCHEMATA'));
		$this->tables = parent::registerNode(new Node(new Tables()));
		$this->columns = parent::registerNode(new Node(new Columns()));
		$this->keys = parent::registerNode((new Node(new KeyColumnUsage(), 'KEY_COLUMN_USAGE')));
		$this->indexes = parent::registerNode((new Node(new Indexes(), 'STATISTICS')));
		parent::__construct('information_schema', false);
	}

}
