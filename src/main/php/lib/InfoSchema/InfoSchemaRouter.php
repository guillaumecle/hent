<?php
namespace Hent\InfoSchema;

use Hent\Node\Node;
use Hent\Router\BaseMySqlConfig;
use Hent\Router\MySqlConfig;
use Hent\Router\MySqlRouter;

class InfoSchemaRouter extends MySqlRouter {

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

	/**
	 * @param MySqlConfig $config
	 */
	public function __construct(MySqlConfig $config) {
		$this->schemata = parent::registerNode(new Node(Schemata::class, SchemataKey::class, 'SCHEMATA'));
		$this->tables = parent::registerNode(new Node(Tables::class, TablesKey::class));
		$this->columns = parent::registerNode(new Node(Columns::class, ColumnsKey::class));
		$this->keys = parent::registerNode(new Node(KeyColumnUsage::class, KeyColumnUsageKey::class, 'KEY_COLUMN_USAGE'));
		$this->indexes = parent::registerNode(new Node(Indexes::class, IndexesKey::class, 'STATISTICS'));
		$infoSchemaConfig = new BaseMySqlConfig(
			$config->getHost(),
			$config->getPort(),
			$config->getUsername(),
			$config->getPassword(),
			'information_schema'
		);
		parent::__construct($infoSchemaConfig, false);
	}

}
