<?php
namespace Hent\InfoSchema;

use Hent\Databean\BaseDatabean;
use Hent\Databean\Lookup;
use Hent\Field\Field;
use Hent\Field\StringField;

class Tables extends BaseDatabean {

	/**
	 * @var TablesKey
	 */
	private $key;

	/**
	 * @var string
	 */
	private $schema;

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var string
	 */
	private $engine;

	public function __construct() {
		$this->key = new TablesKey();
	}

	/**
	 * @return TablesKey
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
	public function getName() {
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getEngine() {
		return $this->engine;
	}

	/**
	 * @return Field[]
	 */
	public function getFields() {
		return [
			(new StringField('name'))->setSqlName('TABLE_NAME'),
			(new StringField('schema'))->setSqlName('TABLE_SCHEMA'),
			(new StringField('engine'))->setSQLName('ENGINE')
		];
	}

	/**
	 * @return Lookup[]
	 */
	public function getIndexes() {
		return [
			new TablesBySchemaLookup(null)
		];
	}

}
