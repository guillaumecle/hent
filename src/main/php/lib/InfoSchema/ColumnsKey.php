<?php
require_once __DIR__.'/../databean/Key.php';
class ColumnsKey implements Key {

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
	 * @param $schema string
	 * @param $tableName string
	 * @param $columnName string
	 */
	function __construct($schema, $tableName, $columnName) {
		$this->schema = $schema;
		$this->tableName = $tableName;
		$this->tableName = $columnName;
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

	/**
	 * @return string
	 */
	public function getColumnName() {
		return $this->columnName;
	}

	public function getFields() {
		return [
			(new BaseField('schema', ColumnType::string()))->setSqlName('TABLE_SCHEMA'),
			(new BaseField('tableName', ColumnType::string()))->setSqlName('TABLE_NAME'),
			(new BaseField('columnName', ColumnType::string()))->setSqlName('COLUMN_NAME')
		];
	}

}
