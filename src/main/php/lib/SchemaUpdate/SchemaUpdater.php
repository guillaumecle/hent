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
		if (!isset(self::$infoSchemaRouter)) {
			self::$infoSchemaRouter = new InfoSchemaRouter();
		}
		$this->router = $router;
	}

	public function updateSchema() {
		$databaseTables = self::$infoSchemaRouter->tables->lookup(new TablesBySchemaLookup($this->router->getSqlName()));
		$tableNames = [];
		/**
		 * @var $table Tables
		 */
		foreach ($databaseTables as $table) {
			$tableNames[] = $table->getName();
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
		$cols = self::$infoSchemaRouter->columns->lookup(new ColumnsByTableLookup($this->router->getSqlName(), $node->getSqlName()));
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
		/**
		 * @var $colByName Columns[]
		 */
		$colByName = [];
		foreach ($cols as $col) {
			$columnsNames[] = $col->getColumnName();
			$colByName[$col->getColumnName()] = $col;
		}
		/**
		 * @var $alters string[]
		 */
		$alters = [];
		foreach ($filedNames as $fieldName) {
			if (in_array($fieldName, $columnsNames)) {
				//checkForUpdate
				$field = $fields[$fieldName];
				$col = $colByName[$fieldName];
				if ($col->getColumnType() != $field->getType()->getMySQLDeclaration()) {
					$sql ='modify column ' . $field->getEscapedSqlName() . ' ' . $fields[$fieldName]->getType()->getMySQLDeclaration();
					$alters[] = $sql;
				}
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
		//primary
		$keyLookup = new KeyColumnsByTableAndName($this->router->getSqlName(), $node->getSqlName(), 'PRIMARY');
		/**
		 * @var KeyColumnUsage[] $keyCols
		 */
		$keyCols = self::$infoSchemaRouter->keys->lookup($keyLookup);
		$dbKeys = [];
		foreach ($keyCols as $col) {
			$dbKeys[intval($col->getPosition()) - 1] = $col->getColumn();
		}
		$pkFields = $node->getDataBean()->getKey()->getFields();
		$pkFieldsName = [];
		foreach ($pkFields as $field) {
			$pkFieldsName[] = $field->getSqlName();
		}
		if ($dbKeys !== $pkFieldsName) {
			if (count($keyCols) > 0) {
				$alters[] = 'drop primary key';
			}
			$createPK = 'add ' . $this->getPrimaryKeyDefinition($pkFields);
			$alters[] = $createPK;
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
		$pkFields = $node->getDataBean()->getKey()->getFields();
		$fields = array_merge($pkFields, $node->getDataBean()->getFields());
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
		if (count($pkFields) > 0) {
			$sql .= ',' . "\n\t";
			$sql .= $this->getPrimaryKeyDefinition($pkFields);
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

	/**
	 * @param Field[] $pkFields
	 * @return string
	 */
	private function getPrimaryKeyDefinition($pkFields) {
		$createPK = 'primary key (';
		$pkFieldsIter = new CachingIterator(new ArrayIterator($pkFields));
		/**
		 * @var Field $field
		 */
		foreach ($pkFieldsIter as $field) {
			$createPK .= $field->getEscapedSqlName();
			if ($pkFieldsIter->hasNext()) {
				$createPK .= ',';
			}
		}
		$createPK .= ')';
		return $createPK;
	}

}
