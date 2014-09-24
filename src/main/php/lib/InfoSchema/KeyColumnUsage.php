<?php
require_once __DIR__.'/../databean/DataBean.php';
require_once 'KeyColumnUsageKey.php';
class KeyColumnUsage implements DataBean {

	private $key;

	private $name;
	private $schema;
	private $table;
	private $column;
	private $position;

	public function __construct() {
		$this->key = new KeyColumnUsageKey();
	}

	/**
	 * @return Key
	 */
	public function getKey() {
		return $this->key;
	}

	/**
	 * @return Field[]
	 */
	public function getFields() {
		return [
				(new BaseField('name', ColumnType::string()))->setSqlName('CONSTRAINT_NAME'),
				(new BaseField('schema', ColumnType::string()))->setSqlName('TABLE_SCHEMA'),
				(new BaseField('table', ColumnType::string()))->setSqlName('TABLE_NAME'),
				(new BaseField('column', ColumnType::string()))->setSqlName('COLUMN_NAME'),
				(new BaseField('position', ColumnType::integer()))->setSqlName('ORDINAL_POSITION')
		];
	}

}
class KeyColumnsByTableAndName implements Lookup {

	/**
	 * @var string
	 */
	private $schema;

	/**
	 * @var string
	 */
	private $tableName;

	/**
	 * @var
	 */
	private $keyName;

	/**
	 * @param $schema string
	 * @param $tableName string
	 * @param $keyName string
	 */
	function __construct($schema, $tableName, $keyName) {
		$this->schema = $schema;
		$this->tableName = $tableName;
		$this->keyName = $keyName;
	}

	public function getFields() {
		return [
			(new BaseField('schema', ColumnType::string()))->setSqlName('TABLE_SCHEMA'),
			(new BaseField('tableName', ColumnType::string()))->setSqlName('TABLE_NAME'),
			(new BaseField('keyName', ColumnType::string()))->setSqlName('CONSTRAINT_NAME')
		];
	}

}
