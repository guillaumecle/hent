<?php
namespace Hent\SchemaUpdate;

use ArrayIterator;
use CachingIterator;
use Hent\Field\Field;
use Hent\InfoSchema\Columns;
use Hent\InfoSchema\ColumnsByTableLookup;
use Hent\InfoSchema\InfoSchemaRouter;
use Hent\InfoSchema\KeyColumnsByTableAndName;
use Hent\InfoSchema\KeyColumnUsage;
use Hent\InfoSchema\SchemataByNameLookup;
use Hent\InfoSchema\Tables;
use Hent\InfoSchema\TablesBySchemaLookup;
use Hent\Node\Node;
use Hent\Router\MySqlConfig;
use Hent\Router\MySqlRouter;
use Hent\Router\Router;
use Hent\Util;

class SchemaUpdater {

	/**
	 * @var InfoSchemaRouter
	 */
	private $infoSchemaRouter;

	/**
	 * @var Router
	 */
	private $router;

	/**
	 * @param MySqlConfig $config
	 * @param MySqlRouter $router
	 */
	public function __construct(MySqlConfig $config, MySqlRouter $router) {
		$this->infoSchemaRouter = new InfoSchemaRouter($config);
		$this->router = $router;
	}

	public function updateSchema() {
		$this->checkAndCreateDatabase();
		$databaseTables = $this->infoSchemaRouter->tables->lookup(new TablesBySchemaLookup($this->router->getSqlName()));
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
		$cols = $this->infoSchemaRouter->columns->lookup(new ColumnsByTableLookup($this->router->getSqlName(), $node->getSqlName()));
		$filedNames = [];
		/**
		 * @var $fields Field[];
		 */
		$fields =[];
		foreach ($node->getDatabean()->getKey()->getFields() as $field) {
			$filedNames[] = $field->getSqlName();
			$fields[$field->getSqlName()] = $field;
		}
		foreach ($node->getDatabean()->getFields() as $field) {
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
		$keyCols = $this->infoSchemaRouter->keys->lookup($keyLookup);
		$dbKeys = [];
		foreach ($keyCols as $col) {
			$dbKeys[$col->getPosition() - 1] = $col->getColumn();
		}
		$pkFields = $node->getDatabean()->getKey()->getFields();
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

		//index

		if (count($alters) > 0) {
			$iterator = new CachingIterator(new ArrayIterator($alters));
			$sql = 'alter table ' . $this->router->getSqlName() . '.' . $node->getEscapedSqlName() . "\n";
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
		$sql = 'create table ' . $this->router->getSqlName() . '.' . $node->getEscapedSqlName() . '(';
		$pkFields = $node->getDatabean()->getKey()->getFields();
		$fields = array_merge($pkFields, $node->getDatabean()->getFields());
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
		$sql = 'drop table ' . $this->router->getSqlName() . '.' . $tableName;
		$this->doQuery($sql);
	}

	/**
	 * @param $sql array
	 * @param $type
	 */
	private function doQuery($sql, $type = null) {
		Util::println('========= will do =========');
		Util::println($sql);
		$start = microtime(true);
		$this->infoSchemaRouter->getConnection()->query($sql);
		$end = microtime(true);
		$duration = round(1000 * ($end - $start));
		Util::println('======= done (' . $duration . 'ms) =======');
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

	private function checkAndCreateDatabase() {
		$databases = $this->infoSchemaRouter->schemata->lookup(new SchemataByNameLookup($this->router->getSqlName()));
		if (count($databases) == 0) {
			$sql = 'create database ' . $this->router->getSqlName();
			$this->doQuery($sql);
		}
	}

}
