<?php
namespace Hent\InfoSchema;

use Hent\Databean\Databean;
use Hent\Databean\Lookup;
use Hent\Field\Field;
use Hent\Field\StringField;

class Schemata implements Databean {

	/**
	 * @var SchemataKey
	 */
	private $key;

	/**
	 * @var string
	 */
	private $schema;

	public function __construct() {
		$this->key = new SchemataKey();
	}

	/**
	 * @return SchemataKey
	 */
	public function getKey() {
		return $this->key;
	}

	/**
	 * @return Lookup[]
	 */
	public function getIndexes() {
		return [
			new SchemataByNameLookup(null)
		];
	}

	/**
	 * @return Field[]
	 */
	public function getFields() {
		return [
			(new StringField('schema'))->setSqlName('SCHEMA_NAME')
		];
	}

}
class SchemataByNameLookup implements Lookup {

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @param string $name;
	 */
	public function __construct($name) {
		$this->name = $name;
	}

	/**
	 * @return Field[]
	 */
	public function getFields() {
		return [
			(new StringField('name'))->setSqlName('SCHEMA_NAME')
		];
	}

}
