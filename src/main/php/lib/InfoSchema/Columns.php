<?php
namespace Hent\InfoSchema;
use Hent\DataBean\Databean;
use Hent\DataBean\Lookup;
use Hent\Field\Field;
use Hent\Field\StringField;

class Columns implements Databean {

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
			(new StringField('schema'))->setSqlName('TABLE_SCHEMA'),
			(new StringField('tableName'))->setSqlName('TABLE_NAME'),
			(new StringField('columnName'))->setSqlName('COLUMN_NAME'),
			(new StringField('columnType'))->setSqlName('COLUMN_TYPE')
		];
	}

	/**
	 * @return Lookup[]
	 */
	public function getIndexes() {
		return [
			new ColumnsByTableLookup(null, null)
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
			(new StringField('schema'))->setSqlName('TABLE_SCHEMA'),
			(new StringField('tableName'))->setSqlName('TABLE_NAME')
		];
	}

}
