<?php
require_once __DIR__.'/../databean/Key.php';
require_once __DIR__.'/../fielder/Fielder.php';
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

	/**
	 * @return ColumnsKeyFielder
	 */
	public function getFielder() {
		return new ColumnsKeyFielder();
	}

}
class ColumnsKeyFielder implements Fielder {

	public function getFields() {
		return [
			(new BaseField('schema', ColumnType::string()))->setSqlName('TABLE_SCHEMA'),
			(new BaseField('tableName', ColumnType::string()))->setSqlName('TABLE_NAME')
		];
	}

}