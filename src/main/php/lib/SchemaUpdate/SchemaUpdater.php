<?php
class SchemaUpdater {

	/**
	 * @var InfoSchemaRouter
	 */
	private static $infoSchemaRouter;

	/**
	 * @var Router
	 */
	private $router;

	/**
	 * @param $router Router
	 */
	public function __construct($router) {
		if (!isset(SchemaUpdater::$infoSchemaRouter)) {
			SchemaUpdater::$infoSchemaRouter = new InfoSchemaRouter();
		}
		$this->router = $router;
	}

	public function updateSchema() {
		$databaseTables = SchemaUpdater::$infoSchemaRouter->tables->lookup(new TablesBySchemaLookup($this->router->getName()));
		$tableNames = [];
		/**
		 * @var $table Tables
		 */
		foreach ($databaseTables as $table) {
			$tableNames[] = $table->getKey()->getName();
		}
		$nodeNames = [];
		foreach ($this->router->getNodes() as $node) {
			$nodeNames[] = $node->getSqlName();
		}
		foreach ($nodeNames as $index => $nodeName) {
			if (in_array($nodeName, $tableNames)) {
				$this->checkForUpdate($this->router->getNodes()[$index]);
			} else {
				$this->create($this->router->getNodes()[$index]);
			}
		}
		foreach ($tableNames as $tableName) {
			if (!in_array($tableName, $nodeNames)) {
				$this->drop($tableName);
			}
		}
		return;
	}

	/**
	 * @param $node Node
	 */
	private function checkForUpdate($node) {
		/**
		 * @var $cols Columns[]
		 */
		$cols = self::$infoSchemaRouter->columns->lookup(new ColumnsByTableLookup($this->router->getName(), $node->getSqlName()));
		$filedNames = [];
		/**
		 * @var $fields Field[];
		 */
		$fields =[];
		foreach ($node->getDataBean()->getKey()->getFields() as $field) {
			$filedNames[] = $field->getSqlName();
			$fields[$field->getSqlName()] = $field;
		}
		foreach ($node->getDataBean()->getFields() as $field) {
			$filedNames[] = $field->getSqlName();
			$fields[$field->getSqlName()] = $field;
		}
		$columnsNames = [];
		foreach ($cols as $col) {
			$columnsNames[] = $col->getKey()->getColumnName();
		}
		/**
		 * @var $alters string[]
		 */
		$alters = [];
		foreach ($filedNames as $fieldName) {
			if (in_array($fieldName, $columnsNames)) {
				//checkForUpdate
			} else {
				//doAdd
				$sql ='add column ' . $fields[$fieldName]->getEscapedSqlName() . ' ' . $fields[$fieldName]->getType()->getMySQLDeclaration();
				$alters[] = $sql;
			}
		}
		foreach ($columnsNames as $columnsName) {
			if (!in_array($columnsName, $filedNames)) {
				//doDelete
				$sql = 'drop column ' . $columnsName;
				$alters[] = $sql;
			}
		}
		if (count($alters) > 0) {
			$iterator = new CachingIterator(new ArrayIterator($alters));
			$sql = 'alter table ' . $node->getEscapedSqlName() . "\n";
			foreach ($iterator as $alter) {
				$sql .= '  ' . $alter;
				if ($iterator->hasNext()) {
					$sql .= ',' . "\n";
				}
			}
			$this->doQuery($sql);
		}
	}

	/**
	 * @param $node Node
	 */
	private function create($node) {
		$sql = 'create table ' . $node->getEscapedSqlName() . '(';
		$fields = array_merge($node->getDataBean()->getKey()->getFields(), $node->getDataBean()->getFields());
		$iterator = new CachingIterator(new ArrayIterator($fields));
		/**
		 * @var $field Field
		 */
		foreach ($iterator as $field) {
			$sql .= "\n\t" . $field->getEscapedSqlName() . ' ' . $field->getType()->getMySQLDeclaration();
			if ($iterator->hasNext()) {
				$sql .= ',';
			}
		}
		$sql .= "\n" . ')';
		$this->doQuery($sql);
	}

	private function drop($tableName) {
		$sql = 'drop table ' . $tableName;
		$this->doQuery($sql);
	}

	/**
	 * @param $sql array
	 * @param $type
	 */
	private function doQuery($sql, $type = null) {
		println('========= will do =========');
		println($sql);
		$start = microtime(true);
		$this->router->getConnection()->query($sql);
		$end = microtime(true);
		$duration = round(1000 * ($end - $start));
		println('======= done (' . $duration . 'ms) =======');
	}

}
