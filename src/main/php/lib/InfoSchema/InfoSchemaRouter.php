<?php
namespace Hent\InfoSchema;
use Hent\Node\Node;
use Hent\Router\Router;

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
