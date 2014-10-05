<?php
namespace Hent\InfoSchema;
use Hent\DataBean\Databean;
use Hent\DataBean\Key;
use Hent\DataBean\Lookup;
use Hent\Field\Field;
use Hent\Field\IntegerField;
use Hent\Field\StringField;

class KeyColumnUsage implements Databean {

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
	 * @return string
	 */
	public function getColumn() {
		return $this->column;
	}

	/**
	 * @return int
	 */
	public function getPosition() {
		return $this->position;
	}

	/**
	 * @return Field[]
	 */
	public function getFields() {
		return [
				(new StringField('name'))->setSqlName('CONSTRAINT_NAME'),
				(new StringField('schema'))->setSqlName('TABLE_SCHEMA'),
				(new StringField('table'))->setSqlName('TABLE_NAME'),
				(new StringField('column'))->setSqlName('COLUMN_NAME'),
				(new IntegerField('position'))->setSqlName('ORDINAL_POSITION')
		];
	}

	/**
	 * @return Lookup[]
	 */
	public function getIndexes() {
		return [
			new KeyColumnsByTableAndName(null, null, null)
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
			(new StringField('schema'))->setSqlName('TABLE_SCHEMA'),
			(new StringField('tableName'))->setSqlName('TABLE_NAME'),
			(new StringField('keyName'))->setSqlName('CONSTRAINT_NAME')
		];
	}

}
