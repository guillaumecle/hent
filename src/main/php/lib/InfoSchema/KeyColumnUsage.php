<?php
namespace Hent\InfoSchema;

use Hent\Databean\BaseDatabean;
use Hent\Databean\Key;
use Hent\Databean\Lookup;
use Hent\Field\Field;
use Hent\Field\IntegerField;
use Hent\Field\StringField;

class KeyColumnUsage extends BaseDatabean {

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