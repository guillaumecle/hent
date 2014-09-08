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
	private $columnType;

	/**
	 * @param $key ColumnsKey
	 */
	public function __construct($key) {
		$this->key = $key;
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
	public function getColumnType() {
		return $this->columnType;
	}

	/**
	 * @return Field[]
	 */
	public function getFields() {
		return [
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
