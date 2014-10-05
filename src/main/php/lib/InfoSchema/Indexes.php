<?php

namespace Hent\InfoSchema;
use Hent\DataBean\Databean;
use Hent\DataBean\Lookup;
use Hent\Field\Field;
use Hent\Field\IntegerField;
use Hent\Field\StringField;

class Indexes implements Databean {

	/**
	 * @var IndexesKey
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
	private $name;

	/**
	 * @var int
	 */
	private $position;

	public function __construct() {
		$this->key = new IndexesKey();
	}

	/**
	 * @return Key
	 */
	public function getKey() {
		return $this->key;
	}

	/**
	 * @return Lookup[]
	 */
	public function getIndexes() {
		return [
			new IndexesByTable(null, null)
		];
	}

	/**
	 * @return Field[]
	 */
	public function getFields() {
		return [
			(new StringField('schema'))->setSqlName('TABLE_SCHEMA'),
			(new StringField('tableName'))->setSqlName('TABLE_NAME'),
			(new StringField('name'))->setSqlName('INDEX_NAME'),
			(new IntegerField('position'))->setSqlName('SEQ_IN_INDEX')
		];
	}

}
class IndexesByTable implements Lookup {

	/**
	 * @var string
	 */
	private $schema;

	/**
	 * @var string
	 */
	private $tableName;

	function __construct($schema, $tableName) {
		$this->schema = $schema;
		$this->tableName = $tableName;
	}

	/**
	 * @return Field[]
	 */
	public function getFields() {
		return [
			(new StringField('schema'))->setSqlName('TABLE_SCHEMA'),
			(new StringField('tableName'))->setSqlName('TABLE_NAME')
		];
	}

}
