<?php
require_once __DIR__.'/../databean/DataBean.php';
require_once __DIR__.'/../databean/Lookup.php';
require_once 'ColumnsKey.php';
class Columns implements DataBean {

	/**
	 * @var ColumnsKey
	 */
	private $key;

	/**
	 * @var string
	 */
	private $schema;

	/**
	 * @var string
	 */
	private $tableName;

	/**
	 * @var string
	 */
	private $columnName;

	/**
	 * @var string
	 */
	private $columnType;

	public function __construct() {
		$this->key = new ColumnsKey();
	}

	/**
	 * @return ColumnsKey
	 */
	public function getKey() {
		return $this->key;
	}

	/**
	 * @return string
	 */
	public function getSchema() {
		return $this->schema;
	}

	/**
	 * @return string
	 */
	public function getColumnName() {
		return $this->columnName;
	}

	/**
	 * @return string
	 */
	public function getColumnType() {
		return $this->columnType;
	}

	/**
	 * @return Field[]
	 */
	public function getFields() {
		return [
			(new BaseField('schema', ColumnType::string()))->setSqlName('TABLE_SCHEMA'),
			(new BaseField('tableName', ColumnType::string()))->setSqlName('TABLE_NAME'),
			(new BaseField('columnName', ColumnType::string()))->setSqlName('COLUMN_NAME'),
			(new BaseField('columnType', ColumnType::string()))->setSqlName('COLUMN_TYPE')
		];
	}
}
class ColumnsByTableLookup implements Lookup {

	/**
	 * @var string
	 */
	private $schema;

	/**
	 * @var string
	 */
	private $tableName;

	/**
	 * @param $schema string
	 * @param $tableName string
	 */
	function __construct($schema, $tableName) {
		$this->schema = $schema;
		$this->tableName = $tableName;
	}

	/**
	 * @return string
	 */
	public function getSchema() {
		return $this->tableName;
	}

	/**
	 * @return string
	 */
	public function getTableName() {
		return $this->tableName;
	}

	public function getFields() {
		return [
			(new BaseField('schema', ColumnType::string()))->setSqlName('TABLE_SCHEMA'),
			(new BaseField('tableName', ColumnType::string()))->setSqlName('TABLE_NAME')
		];
	}

}
